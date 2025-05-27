const admin = require("firebase-admin");
const serviceAccount = require("path/to/serviceAccountKey.json");
admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
  databaseURL: "https://campusconnect-af9ad-default-rtdb.firebaseio.com"
});
const db = admin.firestore();
module.exports = { admin, db };