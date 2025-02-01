import { StyleSheet, View, Text, TextInput, TouchableOpacity, ScrollView, ActivityIndicator,} from "react-native";
import React, { useState, useEffect } from "react";
import { router } from "expo-router";
import { Ionicons as Icon } from "@expo/vector-icons";
import RNPickerSelect from "react-native-picker-select";
import { get, ref, set } from "firebase/database";
import { database } from "../db/DBConfig";

export default function BusinessCreateAccountScreen() {
  const [name, setFullName] = useState("");
  const [email, setEmailAddress] = useState("");
  const [contact, setContact] = useState("");
  const [nic, setNicNumber] = useState("");
  const [address, setAddress] = useState("");
  const [district, setDistrict] = useState("");
  const [outlet_id, setOutlet] = useState<string | null>(null);
  const [rnumber, setrnumber] = useState('');
  const [password, setPassword] = useState("");
  const [isPasswordVisible, setPasswordVisible] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [outlets, setOutlets] = useState<
    { label: string; value: string }[]
  >([]);

  const districts = [
    { label: "Colombo", value: "Colombo" },
    { label: "Gampaha", value: "Gampaha" },
    { label: "Kalutara", value: "Kalutara" },
    { label: "Kandy", value: "Kandy" },
    { label: "Matale", value: "Matale" },
    { label: "Nuwara Eliya", value: "Nuwara Eliya" },
    { label: "Galle", value: "Galle" },
    { label: "Matara", value: "Matara" },
    { label: "Hambantota", value: "Hambantota" },
    { label: "Jaffna", value: "Jaffna" },
    { label: "Kilinochchi", value: "Kilinochchi" },
    { label: "Mannar", value: "Mannar" },
    { label: "Vavuniya", value: "Vavuniya" },
    { label: "Mullaitivu", value: "Mullaitivu" },
    { label: "Batticaloa", value: "Batticaloa" },
    { label: "Ampara", value: "Ampara" },
    { label: "Trincomalee", value: "Trincomalee" },
    { label: "Kurunegala", value: "Kurunegala" },
    { label: "Puttalam", value: "Puttalam" },
    { label: "Anuradhapura", value: "Anuradhapura" },
    { label: "Polonnaruwa", value: "Polonnaruwa" },
    { label: "Badulla", value: "Badulla" },
    { label: "Monaragala", value: "Monaragala" },
    { label: "Ratnapura", value: "Ratnapura" },
    { label: "Kegalle", value: "Kegalle" },
  ];

  useEffect(() => {
      const fetchOutlets = async () => {
          if (district) {
              setIsLoading(true);
              const outletsRef = ref(database, "outlets");
              try {
                  const snapshot = await get(outletsRef);
                  if (snapshot.exists()) {
                      const outletData = snapshot.val();
                      const filteredOutlets = Object.values(outletData)
                          .filter((outlet: any) => outlet.district === district)
                          .map((outlet: any) => ({
                              label: outlet.name,
                              value: outlet.outlet_id,
                          }));

                      setOutlets(filteredOutlets);
                  } else {
                      setOutlets([]);
                      console.log("No outlets found for this district");
                  }
              } catch (error) {
                  console.error("Error fetching outlets:", error);
                  setOutlets([]);
              } finally {
                  setIsLoading(false);
              }
          } else {
              setOutlets([]);
          }
      };
      fetchOutlets();
  }, [district]);

  const togglePasswordVisibility = () => {
    setPasswordVisible(!isPasswordVisible);
  };

  const validateInputs = () => {
      if (
          !name ||
          !email ||
          !contact ||
          !nic ||
          !address ||
          !district ||
          outlet_id === null||
          !password ||
          !rnumber 
      ) {
          alert("All fields are required.");
          return false;
      }
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
          alert("Please enter a valid email address.");
          return false;
      }
      if (!/^[0-9]+$/.test(contact) || contact.length < 10) {
          alert("Please enter a valid contact number.");
          return false;
      }
      if (password.length < 8) {
          alert("Password must be at least 8 characters long.");
          return false;
      }
      return true;
  };

  const checkUniqueFields = async () => {
    const dbRef = ref(database, "consumers");
    const snapshot = await get(dbRef);
    if (snapshot.exists()) {
      const consumers = snapshot.val();
      for (let key in consumers) {
        if (consumers[key].email === email) {
          alert("Email already exists. Please use a different email.");
          return false;
        }
        if (consumers[key].nic === nic) {
          alert("NIC number already exists. Please use your own NIC number.");
          return false;
        }
        if (consumers[key].rnumber === rnumber) {
          alert("Registration number already exists. Please use your own registration number.");
          return false;
        }
      }
    }
    return true;
  };

    const addCustomer = async () => {
        if (!validateInputs()) return;
        setIsLoading(true);

        const isUnique = await checkUniqueFields();
        if (!isUnique) {
            setIsLoading(false);
            return;
        }
         if (!outlet_id) {
            alert("Please select an outlet.");
            setIsLoading(false);
             return;
        }


        const customerData = {
            name,
            email,
            contact,
            nic,
            address,
            district,
            outlet_id,
            password,
            category: "Industry",
            rnumber,
            created_at: Date.now(),
        };
        const cusRef = ref(database, "consumers/" + Date.now());

        try {
            await set(cusRef, customerData);
            alert("Account created successfully!");
            setFullName("");
            setEmailAddress("");
            setContact("");
            setNicNumber("");
            setAddress("");
            setDistrict("");
            setOutlet("");
            setPassword("");
            setrnumber("");

            router.push("/businesshomepage");
        } catch (error) {
            console.error("Error adding customer:", error);
            alert("Failed to create account. Please try again.");
        } finally {
            setIsLoading(false);
        }
    };

  return (
    
    <View style={styles.container}>
      <ScrollView>
        <Text style={styles.title}>Create a New Account</Text>

        <TextInput
          style={styles.input}
          placeholder="Full Name"
          placeholderTextColor="#888"
          onChangeText={setFullName}
          value={name}
        />
        <TextInput
          style={styles.input}
          placeholder="Email Address"
          placeholderTextColor="#888"
          keyboardType="email-address"
          onChangeText={setEmailAddress}
          value={email}
        />
        <TextInput
          style={styles.input}
          placeholder="Contact"
          placeholderTextColor="#888"
          keyboardType="numeric"
          onChangeText={setContact}
          value={contact}
        />
        <TextInput
          style={styles.input}
          placeholder="NIC Number"
          placeholderTextColor="#888"
          onChangeText={setNicNumber}
          value={nic}
        />
        <TextInput
          style={styles.input}
          placeholder="Address"
          placeholderTextColor="#888"
          onChangeText={setAddress}
          value={address}
        />
        <RNPickerSelect
          onValueChange={(value) => setDistrict(value)}
          items={districts}
          style={pickerSelectStyles}
          placeholder={{ label: "Select District", value: null }}
        />
        {district && (
          <RNPickerSelect
            onValueChange={(value) => setOutlet(value)}
            items={outlets}
            style={pickerSelectStyles}
            placeholder={{ label: "Select Outlet", value: null }}
            disabled={isLoading}
          />
        )}
        <TextInput
          style={styles.input}
          placeholder="Registration Number"
          placeholderTextColor="#888"
          onChangeText={setrnumber}
          value={rnumber}
        />

        <View style={styles.passwordContainer}>
          <TextInput
            style={styles.passwordInput}
            placeholder="Password"
            placeholderTextColor="#888"
            secureTextEntry={!isPasswordVisible}
            onChangeText={setPassword}
            value={password}
          />
          <TouchableOpacity onPress={togglePasswordVisibility}>
            <Icon
              name={isPasswordVisible ? "eye-off-outline" : "eye-outline"}
              size={20}
              color="#888"
            />
          </TouchableOpacity>
        </View>

        {isLoading ? (
          <ActivityIndicator
            size="large"
            color="#2776D1"
            style={{ marginVertical: 20 }}
          />
        ) : (
          <TouchableOpacity style={styles.button} onPress={addCustomer}>
            <Text style={styles.buttonText}>Sign Up</Text>
          </TouchableOpacity>
        )}

        <TouchableOpacity
          style={styles.buttonContainer}
          onPress={() => router.push("/login")}
        >
          <Text style={styles.buttonContainerText}>
            Already have an account? Login
          </Text>
        </TouchableOpacity>

        
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop: 20,
    backgroundColor: "#f9f9f9",
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    textAlign: "center",
    marginBottom: 20,
    color: "#000",
  },
  input: {
    backgroundColor: "#fff",
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 5,
    padding: 10,
    marginBottom: 15,
    color: "#000",
    fontSize: 16,
  },
  buttonContainer: {
    marginTop: 15,
    paddingVertical: 10,
  },
  buttonContainerText: {
    fontWeight: "bold",
    textAlign: "center",
    fontSize: 16,
  },
  button: {
    backgroundColor: "#2776D1",
    paddingVertical: 10,
    borderRadius: 30,
  },
  buttonText: {
    textAlign: "center",
    color: "white",
    fontWeight: "bold",
    textTransform: "uppercase",
  },
  passwordInput: {
    flex: 1,
    paddingVertical: 12,
  },
  passwordContainer: {
    flexDirection: "row",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 8,
    paddingHorizontal: 12,
    backgroundColor: "#f9f9f9",
    marginBottom: 15,
  },
});

const pickerSelectStyles = StyleSheet.create({
  inputIOS: {
    flexDirection: "row",
    backgroundColor: "#fff",
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 8,
    marginBottom: 10,
    color: "#000",
    fontSize: 16,
  },
  inputAndroid: {
    flexDirection: "row",
    backgroundColor: "#fff",
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 8,
    marginBottom: 10,
    color: "#000",
    fontSize: 16,
  },
});