node {
    def app

    stage('Clone repository') {
        /* Let's make sure we have the repository cloned to our workspace */

        checkout scm
    }

    stage('Build image') {
        /* This builds the actual image; synonymous to
         * docker build on the command line */
        /*app = docker.build("my-image:${env.BUILD_ID}", "-f ./Reporter/Dockerfile")*/
		app = docker.build("chrisashtear/zomboid-reporter", "./Reporter/")
		app2 = docker.build("chrisashtear/zomboid-reporter-frontend", "./frontend/")
		app3 = docker.build("chrisashtear/zomboid-reporter-backend", "./backend/")
		app4 = docker.build("chrisashtear/zomboid-discobot", "./disco-bot/")
		app5 = docker.build("chrisashtear/zomboid-reporter-nginx", "./nginx/")
    }

    stage('Push image') {
        /* Finally, we'll push the image with two tags:
         * First, the incremental build number from Jenkins
         * Second, the 'latest' tag.
         * Pushing multiple tags is cheap, as all the layers are reused. */
        docker.withRegistry('https://registry.hub.docker.com', 'docker-hub-credentials') {
			app.push("${env.BUILD_NUMBER}")
            app.push("latest")
			app2.push("${env.BUILD_NUMBER}")
            app2.push("latest")
            app3.push("latest")
            app4.push("latest")
            app5.push("latest")
        }
    }
}