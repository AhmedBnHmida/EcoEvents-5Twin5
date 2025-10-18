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
                    cp .env.docker .env || echo ".env.docker not found"
                    chmod -R 775 storage bootstrap/cache || echo "Directory not found"
                    mkdir -p reports
                '''
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                sh '''
                    docker run --rm -v $(pwd):/app \
                    -w /app \
                    --user root \
                    composer:latest \
                    bash -c "
                        git config --global --add safe.directory /app && \
                        composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs
                    "
                '''
            }
        }

        stage('Run Tests') {
            steps {
                sh '''
                    echo "🧪 Running PHPUnit tests..."
                    
                    # Run tests and generate reports
                    docker run --rm -v $(pwd):/app \
                    -w /app \
                    --user root \
                    composer:latest \
                    bash -c "
                        git config --global --add safe.directory /app && \
                        ./vendor/bin/phpunit \
                        --log-junit reports/test-results.xml \
                        --coverage-clover reports/coverage.xml \
                        --coverage-html reports/coverage-html \
                        --stop-on-failure
                    " || echo "Tests completed"
                '''
            }
            post {
                always {
                    junit 'reports/test-results.xml'
                    publishHTML([
                        allowMissing: true,
                        alwaysLinkToLastBuild: true,
                        keepAll: true,
                        reportDir: 'reports/coverage-html',
                        reportFiles: 'index.html',
                        reportName: 'PHPUnit Code Coverage'
                    ])
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh """
                        ${SONAR_SCANNER_HOME}/bin/sonar-scanner \
                        -Dsonar.projectKey=EcoEvents \
                        -Dsonar.projectName="EcoEvents Application" \
                        -Dsonar.projectVersion=${APP_VERSION} \
                        -Dsonar.sources=app \
                        -Dsonar.tests=tests \
                        -Dsonar.php.coverage.reportPaths=reports/coverage.xml \
                        -Dsonar.php.tests.reportPath=reports/test-results.xml \
                        -Dsonar.host.url=${SONAR_HOST_URL}
                    """
                }
            }
        }

        stage("Quality Gate") {
            steps {
                timeout(time: 5, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Cleanup Old Docker Resources') {
            steps {
                sh '''
                    echo "🧹 Cleaning up old Docker containers and images..."
                    docker-compose down || true
                    docker images -q ${DOCKER_IMAGE_APP}:${DOCKER_TAG} | grep -q . && docker rmi -f ${DOCKER_IMAGE_APP}:${DOCKER_TAG} || true
                    docker images -q ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG} | grep -q . && docker rmi -f ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG} || true
                    docker image prune -f
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
                    sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
                }
            }
        }

        stage('Build Docker Images') {
            parallel {
                stage('Build Laravel App Image') {
                    steps {
                        sh 'docker build -t ${DOCKER_IMAGE_APP}:${DOCKER_TAG} .'
                    }
                }
                stage('Build Nginx Image') {
                    steps {
                        sh 'docker build -t ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG} -f Dockerfile.nginx .'
                    }
                }
            }
        }

        stage('Push to DockerHub') {
            parallel {
                stage('Push Laravel App Image') {
                    steps {
                        sh 'docker push ${DOCKER_IMAGE_APP}:${DOCKER_TAG}'
                    }
                }
                stage('Push Nginx Image') {
                    steps {
                        sh 'docker push ${DOCKER_IMAGE_NGINX}:${DOCKER_TAG}'
                    }
                }
            }
        }

        stage('Deploy with Docker Compose') {
            steps {
                sh '''
                    docker-compose down || true
                    docker-compose up -d --build
                    sleep 30
                    docker-compose exec -T app php artisan migrate --force || echo "Migrations failed"
                    docker-compose exec -T app php artisan config:cache || echo "Config cache failed"
                    docker-compose exec -T app php artisan route:cache || echo "Route cache failed"
                '''
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        success {
            echo '🎉 Pipeline completed successfully!'
        }
        failure {
            echo '❌ Pipeline failed!'
        }
    }
}