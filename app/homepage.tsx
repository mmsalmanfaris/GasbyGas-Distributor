import React, { useEffect, useState } from "react";
import { View, Text, TouchableOpacity, Image, StyleSheet } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { AntDesign } from "@expo/vector-icons";
import { router } from "expo-router";
import { database } from "../db/DBConfig";
import { ref, get } from "firebase/database";

const HomePageCustomer: React.FC = () => {
  const [recentRequest, setRecentRequest] = useState<string | null>(null);
  const [totalRequests, setTotalRequests] = useState<number>(0);
  const userId = "customer123"; // Replace with actual user ID

  useEffect(() => {
    const recentRequestRef = ref(database, `users/${userId}/recentRequest`);
    const totalRequestsRef = ref(database, `users/${userId}/totalRequests`);

    get(recentRequestRef).then((snapshot) => {
      if (snapshot.exists()) {
        setRecentRequest(snapshot.val());
      }
    });

    get(totalRequestsRef).then((snapshot) => {
      if (snapshot.exists()) {
        setTotalRequests(snapshot.val());
      }
    });
  }, []);

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Ionicons name="menu" size={24} color="black" onPress={() => router.push("/notification")} />
        <TouchableOpacity style={styles.profileImage} onPress={() => router.push("/profile")}>
          <Image source={require("../assets/images/prfl.png")} style={styles.profileImage} />
        </TouchableOpacity>
      </View>

      <View style={styles.upcomingDeliveryContainer}>
        <Text style={styles.upcomingDeliveryTitle}>Upcoming Delivery</Text>
        <Text style={styles.upcomingDeliveryDate}>2025.01.11</Text>
      </View>

      <View style={styles.requestInfoContainer}>
        <View style={styles.infoBox}>
          <Text style={styles.infoTitle}>Recent Request</Text>
          <Text style={styles.infoValue}>{recentRequest ? new Date(recentRequest).toLocaleDateString() : "No Requests"}</Text>
        </View>
        <View style={styles.infoBox}>
          <Text style={styles.infoTitle}>Total Requests</Text>
          <Text style={styles.infoValue}>{totalRequests}</Text>
        </View>
      </View>

      <TouchableOpacity style={styles.qrCodeContainer}>
        <Image source={require("../assets/images/qr.png")} style={styles.qrCodeImage} />
        <Text style={styles.qrCodeText}>Scan Me</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.requestButton} onPress={() => router.push("/requestpage")}>
        <AntDesign name="pluscircleo" size={20} color="white" />
        <Text style={styles.requestButtonText}> Request Gas</Text>
      </TouchableOpacity>
    </View>
  );
};

export default HomePageCustomer;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    paddingHorizontal: 20,
    paddingTop: 40,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  profileImage: {
    width: 40,
    height: 40,
    borderRadius: 20,
  },
  upcomingDeliveryContainer: {
    alignItems: 'center',
    padding: 15,
    borderWidth: 1,
    borderColor: '#000',
    borderRadius: 10,
    marginBottom: 20,
  },
  upcomingDeliveryTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  upcomingDeliveryDate: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#007BFF',
    marginTop: 5,
  },
  requestInfoContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  infoBox: {
    flex: 1,
    alignItems: 'center',
    padding: 15,
    borderWidth: 1,
    borderColor: '#000',
    borderRadius: 10,
    marginHorizontal: 5,
  },
  infoTitle: {
    fontSize: 16,
    color: '#555',
  },
  infoValue: {
    fontSize: 24,
    color: '#333',
    marginTop: 5,
  },
  qrCodeContainer: {
    alignItems: 'center',
    marginBottom: 20,
    padding: 10,
    borderWidth: 1,
    borderColor: '#000',
    borderRadius: 10,
  },
  qrCodeImage: {
    width: 150,
    height: 150,
    marginBottom: 10,
  },
  qrCodeText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#555',
  },
  requestButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#007BFF',
    paddingVertical: 10,
    borderRadius: 8,
  },
  requestButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 10,
  },
});