import React, { useState, useEffect } from "react";
import { View, Text, TouchableOpacity, StyleSheet, TextInput, Alert, ActivityIndicator } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { router } from "expo-router";
import { ref, push, set, get } from "firebase/database";
import { database } from "../db/DBConfig";

const RequestPage: React.FC = () => {
  const [quantity, setQuantity] = useState(1);
  const [selectedDate, setSelectedDate] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const [outlets, setOutlet] = useState<string | null>(null);
  
  const userId = "customer123"; // Replace this with actual user ID logic
  
  useEffect(() => {
    // Fetch outlet from user profile
    const outletRef = ref(database, `users/${userId}/outlets`);
    get(outletRef).then((snapshot) => {
      if (snapshot.exists()) {
        setOutlet(snapshot.val());
      } else {
        setOutlet("Unknown"); // Default if outlet is missing
      }
    });
  }, []);

  const handleQuantityChange = (type: "increment" | "decrement") => {
    setQuantity((prev) => {
      if (type === "increment" && prev < 3) return prev + 1;
      if (type === "decrement" && prev > 1) return prev - 1;
      return prev;
    });
  };

  const handleSubmit = () => {
    if (!selectedDate) {
      Alert.alert("Error", "Please select a delivery date.");
      return;
    }

    setLoading(true);

    const requestData = {
      consumer_id: userId,
      type: "Industry",
      outlet_id: outlets || "Unknown",
      quantity,
      panel: selectedDate,
      created_at: Date.now(),
      empty_cylinder: "pending",
      payment_status: "pending",
      edelivery: "pending",
      sdelivery: "pending",
      delivery_status: "pending",
      qrcode: "pending"
    };

    const userRequestRef = ref(database, `crequests/`);
    const totalRequestsRef = ref(database, `users/${userId}/totalRequests`);
    const recentRequestRef = ref(database, `users/${userId}/recentRequest`);

    push(userRequestRef, requestData)
      .then(() => {
        // Update the total requests count
        get(totalRequestsRef).then((snapshot) => {
          const currentTotal = snapshot.val() || 0;
          set(totalRequestsRef, currentTotal + quantity);
        });

        // Update the recent request date
        set(recentRequestRef, new Date().toISOString());

        setLoading(false);
        Alert.alert(
          "Request Submitted",
          `You have requested ${quantity} gas cylinders for delivery on ${selectedDate}.`,
          [{ text: "OK", onPress: () => router.push("/bsuccessmessage") }]
        );
      })
      .catch((error) => {
        console.error("Error submitting request:", error);
        setLoading(false);
        Alert.alert("Error", "Failed to submit the request. Please try again.");
      });
  };

  return (
    <View style={styles.container}>
      <View style={styles.modal}>
        <Text style={styles.label}>Quantity</Text>
        <View style={styles.quantityContainer}>
          <TouchableOpacity
            style={styles.quantityButton}
            onPress={() => handleQuantityChange("decrement")}
          >
            <Ionicons name="remove" size={24} color="#007BFF" />
          </TouchableOpacity>
          <TextInput
            style={styles.quantityInput}
            value={String(quantity)}
            editable={false}
          />
          <TouchableOpacity
            style={styles.quantityButton}
            onPress={() => handleQuantityChange("increment")}
          >
            <Ionicons name="add" size={24} color="#007BFF" />
          </TouchableOpacity>
        </View>

        <Text style={styles.label}>Delivery Date</Text>
        <View style={styles.dateContainer}>
          <TouchableOpacity
            style={styles.radioButtonContainer}
            onPress={() => setSelectedDate("Panel A")}
          >
            <View
              style={[
                styles.radioButton,
                selectedDate === "Panel A" && styles.radioButtonSelected,
              ]}
            />
            <Text style={styles.dateText}>First Half of month</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.radioButtonContainer}
            onPress={() => setSelectedDate("Panel B")}
          >
            <View
              style={[
                styles.radioButton,
                selectedDate === "Panel B" && styles.radioButtonSelected,
              ]}
            />
            <Text style={styles.dateText}>Second Half of month</Text>
          </TouchableOpacity>
        </View>

        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit} disabled={loading}>
          {loading ? <ActivityIndicator color="#fff" /> : <Text style={styles.submitButtonText}>Submit</Text>}
        </TouchableOpacity>
      </View>
    </View>
  );
};

export default RequestPage;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "rgba(0, 0, 0, 0.5)",
  },
  modal: {
    width: "90%",
    backgroundColor: "#fff",
    borderRadius: 10,
    padding: 20,
    alignItems: "center",
    shadowColor: "#000",
    shadowOpacity: 0.3,
    shadowOffset: { width: 0, height: 2 },
    shadowRadius: 4,
    elevation: 5,
  },
  dateContainer: {
    alignItems: "flex-start",
    width: "100%",
    marginBottom: 20,
  },
  radioButtonContainer: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 10,
  },
  radioButton: {
    width: 20,
    height: 20,
    borderRadius: 10,
    borderWidth: 2,
    borderColor: "#007BFF",
    marginRight: 10,
  },
  radioButtonSelected: {
    backgroundColor: "#007BFF",
  },
  dateText: {
    fontSize: 16,
    color: "#333",
  },
  label: {
    fontSize: 16,
    fontWeight: "bold",
    marginBottom: 10,
    color: "#333",
  },
  quantityContainer: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 20,
  },
  quantityButton: {
    width: 40,
    height: 40,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#f0f0f0",
    borderRadius: 8,
  },
  quantityInput: {
    width: 50,
    height: 40,
    marginHorizontal: 10,
    textAlign: "center",
    fontSize: 16,
    fontWeight: "bold",
    color: "#333",
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 8,
  },
  submitButton: {
    width: "100%",
    backgroundColor: "#007BFF",
    paddingVertical: 15,
    borderRadius: 12,
    alignItems: "center",
  },
  submitButtonText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "bold",
  },
});
