FROM node:alpine
WORKDIR /usr/src/app
COPY package*.json ./
RUN npm install
# Bundle app source
COPY . .
#EXPOSE 3001
CMD ["npm", "start"]