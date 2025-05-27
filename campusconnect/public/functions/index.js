const functions = require("firebase-functions");
const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const { admin, db } = require("./firebase");


const app = express();
app.use(cors());
app.use(bodyParser.json());

app.post("/signup", async (req, res) => {
  const { name, email, password, role, department } = req.body;
  try {
    const userRecord = await admin.auth().createUser({ email, password, displayName: name });
    await db.collection("users").doc(userRecord.uid).set({ name, email, role, department, createdAt: admin.firestore.FieldValue.serverTimestamp() });
    res.status(200).send({ status: "success" });
  } catch (error) {
    res.status(500).send({ status: "error", message: error.message });
  }
});

app.post("/login/verify", async (req, res) => {
  const { idToken } = req.body;
  try {
    const decodedToken = await admin.auth().verifyIdToken(idToken);
    const uid = decodedToken.uid;
    const userDoc = await db.collection("users").doc(uid).get();
    if (!userDoc.exists) throw new Error("User not found");
    res.status(200).send({ uid, ...userDoc.data() });
  } catch (error) {
    res.status(401).send({ status: "error", message: error.message });
  }
});

app.get("/main/profile/:uid", async (req, res) => {
  try {
    const doc = await db.collection("users").doc(req.params.uid).get();
    if (!doc.exists) return res.status(404).send("User not found");
    res.status(200).json(doc.data());
  } catch (error) {
    res.status(500).send("Error fetching profile: " + error.message);
  }
});

app.get("/admin/users", async (req, res) => {
  try {
    const snapshot = await db.collection("users").get();
    const users = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    res.status(200).json(users);
  } catch (error) {
    res.status(500).send("Failed to retrieve users: " + error.message);
  }
});

app.get("/events", async (req, res) => {
  try {
    const snapshot = await db.collection("events").get();
    const events = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    res.status(200).json(events);
  } catch (error) {
    res.status(500).send("Failed to retrieve events: " + error.message);
  }
});

app.get("/resources", async (req, res) => {
  try {
    const snapshot = await db.collection("resources").get();
    const resources = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    res.status(200).json(resources);
  } catch (error) {
    res.status(500).send("Failed to retrieve resources: " + error.message);
  }
});

exports.api = functions.https.onRequest(app);