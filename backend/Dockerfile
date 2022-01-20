FROM node:16
WORKDIR /usr/src/app
ARG NODE_ENV
ENV NODE_ENV $NODE_ENV
ENV SQL_HOST "hostname"
ENV SQL_USER "username"
ENV SQL_PASS "pass"

COPY package*.json ./
RUN npm install
# Bundle app source
COPY . .
EXPOSE 80

CMD ["npm", "start"]