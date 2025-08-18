pipeline {

    agent any

    stages {
        stage('Checkout Code from GitHub') {
            steps {
                echo 'Mengambil kode terbaru...'
                checkout scm
            }
        }

        stage('Build and Deploy Application') {
            steps {
                
                echo "--- MEMBANGUN IMAGE APLIKASI BARU ---"
                sh 'docker compose build --pull --no-cache'

                echo "--- MEN-DEPLOY SEMUA LAYANAN ---"
                sh 'docker compose up -d'

                echo "--- MEMBERSIHKAN IMAGE DOCKER LAMA ---"
                sh 'docker image prune -f'
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