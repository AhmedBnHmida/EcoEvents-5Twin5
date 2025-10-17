pipeline {
    agent any

    environment {
        DOCKER_IMAGE_APP = "ahmedbenhmida/ecoevents-app"  // Changed
        DOCKER_IMAGE_NGINX = "ahmedbenhmida/ecoevents-nginx"  // Changed
        DOCKER_TAG = "latest"
        APP_VERSION = "1.0.0"
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
                    cp .env.example .env || echo ".env.example not found, using existing .env"
                    chmod -R 775 storage bootstrap/cache || echo "Directory not found"
                '''
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                sh '''
                    docker run --rm -v $(pwd):/app \
                    -w /app \
                    composer:latest \
                    composer install --no-dev --optimize-autoloader --no-scripts
                '''
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