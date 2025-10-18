pipeline {
    agent any

    environment {
        DOCKER_IMAGE_APP = "ahmedbenhmida/ecoevents-app" 
        DOCKER_IMAGE_NGINX = "ahmedbenhmida/ecoevents-nginx"
        DOCKER_TAG = "latest"
        APP_VERSION = "1.0.0"
        SONAR_HOST_URL = 'http://localhost:9000'
        SONAR_SCANNER_HOME = tool 'SonarQubeScanner'
    }

    stages {
        stage('Git Checkout') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: 'CI/CD_setup']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/AhmedBnHmida/EcoEvents-5Twin5.git',
                        credentialsId: 'AhmedBnHmida-GIT'
                    ]]
                ])
            }
        }

        stage('Setup Environment') {
            steps {
                sh '''
                    echo "🔧 Setting up environment..."
                    cp .env.docker .env || echo ".env.docker not found, using existing .env"
                    chmod -R 775 storage bootstrap/cache || echo "Directory not found, continuing..."
                    mkdir -p reports
                    chmod -R 777 reports
                '''
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                sh '''
                    echo "📦 Installing PHP dependencies..."
                    
                    docker run --rm -v $(pwd):/app \
                    -w /app \
                    --user root \
                    composer:latest \
                    bash -c "
                        git config --global --add safe.directory /app
                        
                        # Install with dev dependencies for testing
                        composer install --optimize-autoloader --no-scripts --ignore-platform-reqs
                        
                        # Verify critical packages
                        echo '🔍 Verifying test dependencies...'
                        if [ -f './vendor/bin/phpunit' ]; then
                            ./vendor/bin/phpunit --version
                            echo '✅ PHPUnit is available'
                        else
                            echo '❌ PHPUnit not found in vendor/bin/'
                            ls -la vendor/bin/ || echo 'Vendor bin directory not found'
                            exit 1
                        fi
                    "
                '''
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                    echo "🧪 Running PHPUnit tests in Docker..."
                    docker run --rm -v $(pwd):/app -w /app composer:latest ./vendor/bin/phpunit --stop-on-failure
                '''
            }
            
            post {
                always {
                    junit 'reports/test-results.xml'
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh """
                        echo "🔍 Running SonarQube analysis..."
                        ${SONAR_SCANNER_HOME}/bin/sonar-scanner \
                        -Dsonar.projectKey=EcoEvents \
                        -Dsonar.projectName="EcoEvents Application" \
                        -Dsonar.projectVersion=${APP_VERSION} \
                        -Dsonar.sources=app \
                        -Dsonar.tests=tests \
                        -Dsonar.php.coverage.reportPaths=reports/coverage.xml \
                        -Dsonar.php.tests.reportPath=reports/test-results.xml \
                        -Dsonar.host.url=${SONAR_HOST_URL} \
                        -Dsonar.scm.disabled=true
                    """
                }
            }
        }

        stage("Quality Gate") {
            steps {
                timeout(time: 5, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: false
                }
            }
        }

        stage('Cleanup Old Docker Resources') {
            steps {
                sh '''
                    echo "🧹 Cleaning up old Docker resources..."
                    
                    # Stop and remove containers
                    docker-compose down --remove-orphans || true
                    
                    # Remove specific images if they exist
                    if docker images | grep -q "${DOCKER_IMAGE_APP}"; then
                        docker rmi -f ${DOCKER_IMAGE_APP}:${DOCKER_TAG} || true
                    fi
                    
                    if docker images | grep -q "${DOCKER_IMAGE_NGINX}"; then
                        docker rmi -f ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG} || true
                    fi
                    
                    # Clean up dangling images and containers
                    docker system prune -f || true
                    echo "✅ Cleanup completed"
                '''
            }
        }

        stage('Docker Login') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    sh '''
                        echo "🔐 Logging into Docker Hub..."
                        echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin
                        echo "✅ Docker login successful"
                    '''
                }
            }
        }

        stage('Build Docker Images') {
            parallel {
                stage('Build Laravel App Image') {
                    steps {
                        sh '''
                            echo "🐳 Building Laravel application image..."
                            docker build -t ${DOCKER_IMAGE_APP}:${DOCKER_TAG} .
                            echo "✅ App image built successfully"
                        '''
                    }
                }
                stage('Build Nginx Image') {
                    steps {
                        sh '''
                            echo "🐳 Building Nginx image..."
                            docker build -t ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG} -f Dockerfile.nginx .
                            echo "✅ Nginx image built successfully"
                        '''
                    }
                }
            }
        }

        stage('Push to DockerHub') {
            parallel {
                stage('Push Laravel App Image') {
                    steps {
                        sh '''
                            echo "📤 Pushing Laravel app image to Docker Hub..."
                            docker push ${DOCKER_IMAGE_APP}:${DOCKER_TAG}
                            echo "✅ App image pushed successfully"
                        '''
                    }
                }
                stage('Push Nginx Image') {
                    steps {
                        sh '''
                            echo "📤 Pushing Nginx image to Docker Hub..."
                            docker push ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG}
                            echo "✅ Nginx image pushed successfully"
                        '''
                    }
                }
            }
        }

        stage('Deploy with Docker Compose') {
            steps {
                sh '''
                    echo "🚀 Deploying application..."
                    
                    # Stop existing containers
                    docker-compose down --remove-orphans || true
                    
                    # Start new deployment
                    docker-compose up -d --build
                    
                    # Wait for services to be ready
                    echo "⏳ Waiting for services to start..."
                    sleep 30
                    
                    # Run database migrations
                    echo "🔄 Running database migrations..."
                    docker-compose exec -T app php artisan migrate --force || echo "⚠️ Migrations failed or already up to date"
                    
                    # Cache configuration
                    echo "⚡ Caching configuration..."
                    docker-compose exec -T app php artisan config:cache || echo "⚠️ Config cache failed"
                    
                    # Cache routes
                    docker-compose exec -T app php artisan route:cache || echo "⚠️ Route cache failed"
                    
                    echo "✅ Deployment completed successfully"
                    
                    # Show running containers
                    echo "📊 Current running containers:"
                    docker-compose ps
                '''
            }
        }
    }

    post {
        always {
            echo "🧹 Cleaning workspace..."
            cleanWs()
        }
        success {
            echo '🎉 Pipeline completed successfully!'
            emailext (
                subject: "✅ Pipeline Successful: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """
                The Jenkins pipeline completed successfully!
                
                Project: ${env.JOB_NAME}
                Build: #${env.BUILD_NUMBER}
                Status: SUCCESS
                URL: ${env.BUILD_URL}
                
                All stages completed without errors.
                """,
                to: "dev-team@example.com"
            )
        }
        failure {
            echo '❌ Pipeline failed!'
            emailext (
                subject: "❌ Pipeline Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: """
                The Jenkins pipeline failed!
                
                Project: ${env.JOB_NAME}
                Build: #${env.BUILD_NUMBER}
                Status: FAILED
                URL: ${env.BUILD_URL}
                
                Please check the build logs for details.
                """,
                to: "dev-team@example.com"
            )
        }
        unstable {
            echo '⚠️ Pipeline completed with unstable status!'
        }
    }
}