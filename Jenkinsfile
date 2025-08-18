pipeline {
    agent any

    stages {
        stage('Checkout Code from GitHub') {
            steps {
                echo 'Mengambil kode dari SCM yang sudah dikonfigurasi...'

                checkout scm
            }
        }
        stage('Deploy to Production Server') {
            steps {
                echo 'Mempersiapkan untuk deploy ke VPS...'
                sshagent(['vps-ssh-key']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no jenkins@localhost '
                            echo "--- BERHASIL LOGIN KE VPS SEBAGAI USER JENKINS ---"
                            
                            # Pindah ke direktori kerja utama
                            cd /var/www/

                            echo "--- MEMBANGUN IMAGE APLIKASI BARU ---"
                            docker-compose build --pull --no-cache app-ubmager

                            echo "--- MENJALANKAN MIGRASI DATABASE ---"
                            docker-compose run --rm app-ubmager

                            echo "--- MEN-DEPLOY SEMUA LAYANAN ---"
                            docker-compose up -d

                            echo "--- MEMBERSIHKAN IMAGE DOCKER LAMA ---"
                            # Menghemat ruang disk di server
                            docker image prune -f

                            echo "--- DEPLOY SELESAI DENGAN SUKSES ---"
                        '
                    '''
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