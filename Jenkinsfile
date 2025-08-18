pipeline {
    agent any

    stages {
        stage('Checkout Jenkinsfile') {
            steps {
                checkout scm
            }
        }
        stage('Deploy to Production Server') {
            steps {
                sshagent(['jenkins-ssh-key']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no jenkins@localhost '
                            
                            cd /var/www/UBMagerAPI

                            echo "--- Mengambil kode aplikasi terbaru ---"
                            git pull origin dev-cicd
                            cd /var/www/

                            echo "--- Membangun image baru ---"
                            docker-compose build --pull --no-cache app-ubmager
                            
                            docker-compose run --rm app-ubmager
                            
                            echo "--- Men-deploy layanan ---"
                            docker-compose up -d

                            echo "--- Membersihkan sampah Docker ---"
                            docker image prune -f
                        '
                    '''
                }
            }
        }
    }
}