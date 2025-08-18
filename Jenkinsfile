pipeline {
    agent any

    stages {

        stage('Prepare Workspace on Host') {
            steps {
                ws('/var/www/UBMagerAPI') {
                    
                    checkout scm
                }
            }
        }

        stage('Build and Deploy Application') {
            steps {
                dir('/var/www/') {
                    
                    echo "--- Membangun image aplikasi baru ---"
                    sh 'docker-compose build --pull --no-cache app-ubmager'

                    echo "--- Men-deploy semua layanan ---"
                    sh 'docker-compose up -d'

                    echo "--- Membersihkan image Docker lama ---"
                    sh 'docker image prune -f'
                }
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        success {
            echo 'Pipeline berhasil!'
        }
        failure {
            echo 'Pipeline GAGAL!'
        }
    }
}