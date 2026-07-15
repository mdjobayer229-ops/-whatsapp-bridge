FROM node:20-slim
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install --production --ignore-scripts
COPY . .
EXPOSE 8080
CMD ["node", "index.js"]
