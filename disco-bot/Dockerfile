FROM node:16
WORKDIR /usr/src/app
ARG NODE_ENV
ENV NODE_ENV $NODE_ENV
ENV CLIENT_TOKEN "ENTER TOKEN"
ENV BACKEND_HTTPS "false"
ENV API_HOST "z-backend"
ENV API_PORT 80

COPY package*.json ./
RUN npm install
# Bundle app source
COPY . .

CMD ["npm", "start"]