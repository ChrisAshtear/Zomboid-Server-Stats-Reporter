FROM node:16
WORKDIR /usr/src/app
ARG NODE_ENV
ENV NODE_ENV $NODE_ENV
ENV SQL_HOST "hostname"
ENV SQL_USER "username"
ENV SQL_PASS "pass"
ENV API_PORT 3001

COPY package*.json ./
RUN npm install
# Bundle app source
COPY . .
EXPOSE 3001

CMD ["npm", "start"]