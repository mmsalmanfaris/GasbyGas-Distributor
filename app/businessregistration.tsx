import { StyleSheet, View, Text, TextInput, TouchableOpacity, ScrollView, ActivityIndicator } from 'react-native';
import React, { useState } from 'react';
import { router } from 'expo-router';
import Icon from 'react-native-vector-icons/Ionicons';
import RNPickerSelect from 'react-native-picker-select';
import { get, ref, set } from 'firebase/database';
import { database } from '../db/DBConfig';

export default function CreateAccountScreen() {
  const [name, setFullName] = useState('');
  const [email, setEmailAddress] = useState('');
  const [contact, setContact] = useState('');
  const [nic, setNicNumber] = useState('');
  const [address, setAddress] = useState('');
  const [district, setDistrict] = useState('');  
  const [shopName, setShopName] = useState('');
  const [rnumber, setrnumber] = useState('');
  const [outlet_id, setOutlet_id] = useState('');
  const [password, setPassword] = useState('');
  const [isPasswordVisible, setPasswordVisible] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const districts = [
    { label: 'Colombo', value: 'Colombo' },
    { label: 'Gampaha', value: 'Gampaha' },
    { label: 'Kalutara', value: 'Kalutara' },
    { label: 'Kandy', value: 'Kandy' },
    { label: 'Matale', value: 'Matale' },
    { label: 'Nuwara Eliya', value: 'Nuwara Eliya' },
    { label: 'Galle', value: 'Galle' },
    { label: 'Matara', value: 'Matara' },
    { label: 'Hambantota', value: 'Hambantota' },
    { label: 'Jaffna', value: 'Jaffna' },
    { label: 'Kilinochchi', value: 'Kilinochchi' },
    { label: 'Mannar', value: 'Mannar' },
    { label: 'Vavuniya', value: 'Vavuniya' },
    { label: 'Mullaitivu', value: 'Mullaitivu' },
    { label: 'Batticaloa', value: 'Batticaloa' },
    { label: 'Ampara', value: 'Ampara' },
    { label: 'Trincomalee', value: 'Trincomalee' },
    { label: 'Kurunegala', value: 'Kurunegala' },
    { label: 'Puttalam', value: 'Puttalam' },
    { label: 'Anuradhapura', value: 'Anuradhapura' },
    { label: 'Polonnaruwa', value: 'Polonnaruwa' },
    { label: 'Badulla', value: 'Badulla' },
    { label: 'Monaragala', value: 'Monaragala' },
    { label: 'Ratnapura', value: 'Ratnapura' },
    { label: 'Kegalle', value: 'Kegalle' },
  ];

  const outlet: { [key: string]: string[] } = {
    Gampaha: ['Outlet A', 'Outlet B'],
    Kandy: ['Outlet X', 'Outlet Y'],
    Ampara: ['Kalmunai', 'Pottuvil','Akkaraipattu','Sainthamaruthu','Maruthamunai'],
    Anuradhapura: ['Medawachchiya', 'Kekirawa'],
    Badulla: ['Bandarawela', 'Haputale'],
    Batticaloa: ['Kattankudy', 'Valaichchenai'],
    Colombo: ['Battaramulla', 'Dehiwala','Nugegoda'],
    Galle: ['Ambalangoda', 'Hikkaduwa'],
    Hambantota: ['Tangalle', 'Ambalantota'],
    Jaffna: ['Chavakachcheri', 'Point Pedro'],
    Kalutara: ['Horana', 'Panadura'],
    Kegalle: ['Mawanella', 'Rambukkana'],
    Kilinochchi: ['Kilinochchi', 'Poonakary'],
    Kurunegala: ['Kuliyapitiya', 'Narammala'],
    Mannar: ['Mannar', 'Nanaddan'],
    Matale: ['Dambulla', 'Galewela'],
    Matara: ['Akuressa', 'Weligama'],
    Monaragala: ['Badalkumbura', 'Bibile'],
    Mullaitivu: ['Mullaitivu', 'Oddusuddan'],
    NuwaraEliya: ['Nuwara Eliya', 'Hatton'],
    Polonnaruwa: ['Polonnaruwa', 'Hingurakgoda'],
    Puttalam: ['Chilaw', 'Wennappuwa'],
    Ratnapura: ['Balangoda', 'Embilipitiya'],
    Trincomalee: ['Trincomalee', 'Kinniya'],
    Vavuniya: ['Vavuniya', 'Nedunkerni'],
    
  };

  const togglePasswordVisibility = () => {
    setPasswordVisible(!isPasswordVisible);
  };

  const validateInputs = () => {
    if (!name || !email || !contact || !nic || !address || !district || !password || !rnumber) {
      alert('All fields are required.');
      return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Please enter a valid email address.');
      return false;
    }
    if (!/^[0-9]+$/.test(contact) || contact.length < 10) {
      alert('Please enter a valid contact number.');
      return false;
    }
    if (password.length < 8) {
      alert('Password must be at least 8 characters long.');
      return false;
    }
    return true;
  };

  const checkUniqueFields = async () => {
    const dbRef = ref(database, 'consumers');
    const snapshot = await get(dbRef);
    if (snapshot.exists()) {
      const consumers = snapshot.val();
      for (let key in consumers) {
        if (consumers[key].email === email) {
          alert('Email already exists. Please use a different email.');
          return false;
        }
        if (consumers[key].rnumber === rnumber) {
          alert('Registration number already exists. Please use a different one.');
          return false;
        }
        if (consumers[key].nic === nic) {
          alert('NIC number already exists. Please use your own NIC number.');
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

    const customerData = { name, email, contact, nic, address, shopName, rnumber, district, outlet_id, password };
    const cusRef = ref(database, 'consumers/' + Date.now());

    try {
      await set(cusRef, customerData);
      alert('Account created successfully!');
      
      setFullName('');
      setEmailAddress('');
      setContact('');
      setNicNumber('');
      setAddress('');
      setShopName('');
      setrnumber('');
      setDistrict('');
      setOutlet_id('');
      setPassword('');

      router.push('/businesshomepage');
    } catch (error) {
      console.error('Error adding customer:', error);
      alert('Failed to create account. Please try again.');
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
          placeholder={{ label: 'Select District', value: null }}
        />
        {district && (
          <RNPickerSelect
            onValueChange={(value) => setOutlet_id(value)}
            items={
              outlet[district]?.map((outlet: any) => ({ label: outlet, value: outlet })) || []
            }
            style={pickerSelectStyles}
            placeholder={{ label: 'Select Outlet', value: null }}
          />
        )}

        <TextInput
          style={styles.input}
          placeholder="Shop Name"
          placeholderTextColor="#888"
          onChangeText={setShopName}
          value={shopName}
        />
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
              name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
              size={20}
              color="#888"
            />
          </TouchableOpacity>
        </View>

        {isLoading ? (
          <ActivityIndicator size="large" color="#2776D1" style={{ marginVertical: 20 }} />
        ) : (
          <TouchableOpacity style={styles.button} onPress={addCustomer}>
            <Text style={styles.buttonText}>Sign Up</Text>
          </TouchableOpacity>
        )}

        <TouchableOpacity
          style={styles.buttonContainer}
          onPress={() => router.push('/login')}>
          <Text style={styles.buttonContainerText}>Already have an account? Login</Text>
        </TouchableOpacity>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop: 20,
    backgroundColor: '#f9f9f9',
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 20,
    color: '#000',
  },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 5,
    padding: 10,
    marginBottom: 15,
    color: '#000',
    fontSize: 16,
  },
  buttonContainer: {
    marginTop: 15,
    paddingVertical: 10,
  },
  buttonContainerText: {
    fontWeight: 'bold',
    textAlign: 'center',
    fontSize: 16,
  },
  button: {
    backgroundColor: '#2776D1',
    paddingVertical: 10,
    borderRadius: 30,
  },
  buttonText: {
    textAlign: 'center',
    color: 'white',
    fontWeight: 'bold',
    textTransform: 'uppercase',
  },
  passwordInput: {
    flex: 1,
    paddingVertical: 12,
  },
  passwordContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    paddingHorizontal: 12,
    backgroundColor: '#f9f9f9',
    marginBottom: 15,
  },
  inputAndroid: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    marginBottom: 10,
    color: '#000',
    fontSize: 16,
  },
});

const pickerSelectStyles = StyleSheet.create({
  inputIOS: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    marginBottom: 10,
    color: '#000',
    fontSize: 16,
  },
  inputAndroid: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    marginBottom: 10,
    color: '#000',
    fontSize: 16,
  },
});