import React, { useState, useEffect } from "react";
import { View, Text, TouchableOpacity, StyleSheet, TextInput, Alert, ActivityIndicator } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { router } from "expo-router";
import { ref, push, set, get, query, orderByChild, equalTo } from "firebase/database";
import { database } from "../db/DBConfig";
import AsyncStorage from "@react-native-async-storage/async-storage";

const RequestPage: React.FC = () => {
  const [quantity, setQuantity] = useState(1);
  const [selectedDate, setSelectedDate] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const [selectedCylinder, setSelectedCylinder] = useState<keyof typeof cylinderPrices>('small_2kg');
  const [requestCount, setRequestCount] = useState(0);

  const cylinderPrices = {
    small_2kg: 950.00,
    medium_5kg: 1700.00,
    large_12kg: 4000.00
  };

  useEffect(() => {
    const fetchRequestCount = async () => {
      const consumerId = await AsyncStorage.getItem("consumer_id");
      if (!consumerId) return;

      const currentMonth = new Date().getMonth() + 1;
      const requestRef = query(ref(database, "crequests"), orderByChild("consumer_id"), equalTo(consumerId));
      get(requestRef).then((snapshot) => {
        if (snapshot.exists()) {
          const requests = Object.values(snapshot.val());
          const monthRequests = requests.filter((req: any) => new Date(req.created_at).getMonth() + 1 === currentMonth);
          setRequestCount(monthRequests.length);
        }
      });
    };
    fetchRequestCount();
  }, []);

  const handleQuantityChange = (type: "increment" | "decrement") => {
    setQuantity((prev) => {
      if (type === "increment" && prev < 3) return prev + 1;
      if (type === "decrement" && prev > 1) return prev - 1;
      return prev;
    });
  };

  const handleSubmit = async () => {
    if (!selectedDate) {
        Alert.alert("Error", "Please select a delivery date.");
        return;
    }

    if (requestCount >= 3) {
        Alert.alert("Limit Reached", "You have already made the maximum 3 requests for this month.");
        return;
    }

    setLoading(true);
    const formattedDate = new Date().toISOString();
    const consumerId = await AsyncStorage.getItem("consumer_id");
    if (!consumerId) {
        setLoading(false);
        Alert.alert("Error", "User not found.");
        return;
    }

    const requestData = {
        consumer_id: consumerId,
        type: "Home",
        quantity,
        panel: selectedDate,
        cylinder_type: selectedCylinder,
        total_price: cylinderPrices[selectedCylinder] * quantity,
        created_at: formattedDate,
        empty_cylinder: "pending",
        payment_status: "pending",
        delivery_status: "pending",
        qrcode: "pending"
    };

    // Store the request in the 'crequests' table
    push(ref(database, "crequests"), requestData)
        .then(() => {
            // Now, update the 'totalRequests' in the 'crequest' table for this consumer
            const consumerRef = ref(database, `crequest/${consumerId}`);
            get(consumerRef).then((snapshot) => {
                if (snapshot.exists()) {
                    const consumerData = snapshot.val();
                    const currentTotalRequests = consumerData.totalRequests || 0;
                    const newTotalRequests = currentTotalRequests + 1;

                    // Update totalRequests for this consumer
                    set(consumerRef, {
                        ...consumerData,
                        totalRequests: newTotalRequests,
                    }).then(() => {
                        // After successful update, update local request count
                        setRequestCount(newTotalRequests);

                        setLoading(false);
                        Alert.alert(
                            "Request Submitted",
                            `You have requested ${quantity} ${selectedCylinder} gas cylinders for delivery on ${selectedDate}.`,
                            [{ text: "OK", onPress: () => router.push("/successmessage") }]
                        );
                    }).catch((error) => {
                        setLoading(false);
                        console.error("Error updating totalRequests:", error);
                        Alert.alert("Error", "Failed to update total requests.");
                    });
                } else {
                    // If no consumer data found, initialize it
                    set(consumerRef, {
                        totalRequests: 1,
                    }).then(() => {
                        setRequestCount(1);
                        setLoading(false);
                        Alert.alert(
                            "Request Submitted",
                            `You have requested ${quantity} ${selectedCylinder} gas cylinders for delivery on ${selectedDate}.`,
                            [{ text: "OK", onPress: () => router.push("/successmessage") }]
                        );
                    }).catch((error) => {
                        setLoading(false);
                        console.error("Error initializing consumer data:", error);
                        Alert.alert("Error", "Failed to initialize consumer data.");
                    });
                }
            }).catch((error) => {
                setLoading(false);
                console.error("Error fetching consumer data:", error);
                Alert.alert("Error", "Failed to fetch consumer data.");
            });
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
        <Text style={styles.label}>Select Cylinder Type</Text>
        <View style={styles.cylinderContainer}>
          {Object.keys(cylinderPrices).map((key) => (
            <TouchableOpacity
              key={key}
              style={[styles.cylinderButton, selectedCylinder === key && styles.selectedCylinder]}
              onPress={() => setSelectedCylinder(key as keyof typeof cylinderPrices)}
            >
              <Text>{key.charAt(0).toUpperCase() + key.slice(1)} Cylinder - Rs.{cylinderPrices[key as keyof typeof cylinderPrices].toFixed(2)}</Text>
            </TouchableOpacity>
          ))}
        </View>

        <Text style={styles.label}>Quantity</Text>
        <View style={styles.quantityContainer}>
          <TouchableOpacity style={styles.quantityButton} onPress={() => handleQuantityChange("decrement")}> 
            <Ionicons name="remove" size={24} color="#007BFF" />
          </TouchableOpacity>
          <TextInput style={styles.quantityInput} value={String(quantity)} editable={false} />
          <TouchableOpacity style={styles.quantityButton} onPress={() => handleQuantityChange("increment")}> 
            <Ionicons name="add" size={24} color="#007BFF" />
          </TouchableOpacity>
        </View>

        <Text style={styles.label}>Delivery Date</Text>
        <View style={styles.dateContainer}>
          <TouchableOpacity style={styles.radioButtonContainer} onPress={() => setSelectedDate("A")}>
            <View style={[styles.radioButton, selectedDate === "A" && styles.radioButtonSelected]} />
            <Text style={styles.dateText}>First Half of month</Text>
          </TouchableOpacity>
          <TouchableOpacity style={styles.radioButtonContainer} onPress={() => setSelectedDate("B")}>
            <View style={[styles.radioButton, selectedDate === "B" && styles.radioButtonSelected]} />
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
    backgroundColor: "rgba(145, 145, 145, 0.5)",
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
  cylinderContainer: {
    flexDirection: "column",
    justifyContent: "space-between",
    marginBottom: 20,
  },
  cylinderButton: {
    padding: 10,
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 8,
    marginHorizontal: 5,
    alignItems: "center",
  },
  selectedCylinder: {
    borderColor: "#007BFF",
    borderWidth: 2,
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
