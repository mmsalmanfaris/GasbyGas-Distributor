import React, { useEffect, useState } from "react";
import { View, Text, TouchableOpacity, Image, StyleSheet, Alert, BackHandler } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { AntDesign } from "@expo/vector-icons";
import { router, useFocusEffect } from "expo-router";
import { database } from "../db/DBConfig";
import { ref, get, set } from "firebase/database";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { getAuth } from "firebase/auth";

const auth = getAuth();

const HomePageCustomer: React.FC = () => {
    const [recentRequest, setRecentRequest] = useState<string | null>(null);
    const [totalRequests, setTotalRequests] = useState<number>(0);
    const [consumerId, setConsumerId] = useState<string | null>(null);
    const [outletId, setOutletId] = useState<string | null>(null);
    const [stock, setStock] = useState<number | null>(null);
    const [isHeadOfficeAvailable, setIsHeadOfficeAvailable] = useState<boolean>(true);

    useFocusEffect(
        React.useCallback(() => {
            const onBackPress = () => true; // Prevent back navigation
            const subscription = BackHandler.addEventListener('hardwareBackPress', onBackPress);
            return () => subscription.remove();
        }, [])
    );

    useEffect(() => {
        const fetchInitialData = async () => {
            try {
                
                const storedConsumerId = await AsyncStorage.getItem("consumer_id");
                setConsumerId(storedConsumerId);
                if (storedConsumerId) {                   
                    const user = auth.currentUser;
                    if (user) {
                       const consumersRef = ref(database, "consumers");
                       const snapshot = await get(consumersRef);
                        if (snapshot.exists()) {
                           const consumersData = snapshot.val();
                            for (const consumerId in consumersData) {
                              if (consumersData[consumerId].email === user.email) {
                                setOutletId(consumersData[consumerId].outlet_id);
                                const userRef = ref(database, 'consumers/${consumerId}');                                                            
                                get(userRef).then((snapshot) => {
                                    if (!snapshot.exists()) {
                                          set(userRef, { recentRequest: null, totalRequests: 0 });
                                      } else {
                                         const data = snapshot.val();
                                        setRecentRequest(data.recentRequest || null);
                                        setTotalRequests(data.totalRequests || 0);
                                      }
                                  });
                                  break;
                                }
                           }
                         }
                    }
                 }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };
        fetchInitialData();
    }, []);

    useEffect(() => {
      const fetchHeadOfficeStatus = async () => {
          try {
              const headOfficeRef = ref(database, "headoffice/head_office_id_1");
              const snapshot = await get(headOfficeRef);
              if (snapshot.exists()) {
                 const headOfficeData = snapshot.val();
                 setIsHeadOfficeAvailable(headOfficeData.is_available !== false);
              } else {
                  setIsHeadOfficeAvailable(true);
                  console.log("No head office data");
             }
          } catch (error) {
                console.error("Error fetching head office data:", error);
                 setIsHeadOfficeAvailable(true);
          }
      };
      fetchHeadOfficeStatus();
    }, []);


    useEffect(() => {
        const fetchOutletData = async () => {
            if (outletId) {
                try {
                    const outletRef = ref(database, 'outlets/${outletId}');
                    const outletSnapshot = await get(outletRef);
                    if (outletSnapshot.exists()) {
                        const outletData = outletSnapshot.val();
                        setStock(outletData.stock || 0);
                    } else {
                        setStock(null);
                        console.log("No outlet data found for this user");
                    }
                } catch (error) {
                    console.error("Error fetching outlet data:", error);
                    setStock(null);
                }
            }
        };
        fetchOutletData();
    }, [outletId]);

    const handleRequestGas = () => {
        if (!isHeadOfficeAvailable) {
          Alert.alert(
            "Out of Stock",
            "Sorry, You can't request for Gas now, due to out of stock, Please try again later."
          );
        } else {
          router.push("/requestpage");
        }
      };

    return (
        <View style={styles.container}>
            <Text style={styles.consumerIdText}>Consumer ID: {consumerId}</Text>
            <View style={styles.header}>
                <Ionicons name="menu" size={24} color="black" onPress={() => router.push("/notification")} />
                <TouchableOpacity style={styles.profileImage} onPress={() => router.push("/profile")}>
                    <Image source={require("../assets/images/prfl.png")} style={styles.profileImage} />
                </TouchableOpacity>
            </View>

            <View style={styles.upcomingDeliveryContainer}>
                <Text style={styles.upcomingDeliveryTitle}>Upcoming Delivery</Text>
                <Text style={styles.upcomingDeliveryDate}></Text>
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

            <TouchableOpacity style={styles.requestButton} onPress={handleRequestGas}>
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
     consumerIdText: {
       fontSize: 16,
       fontWeight: "bold",
       marginBottom: 10,
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