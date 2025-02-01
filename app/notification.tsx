import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { router } from 'expo-router';

const NotificationPage = () => {
  const navigation = useNavigation();
  const [activeTab, setActiveTab] = useState('Notifications');
  
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: '#fff' }}>
      <View style={styles.header}>
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'Notifications' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('Notifications')}
        >
          <Text style={[styles.tabText, activeTab === 'Notifications' && styles.activeTabText]}>Notifications</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'Activities' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('Activities')}
        >
          <Text style={[styles.tabText, activeTab === 'Activities' && styles.activeTabText]}>Activities</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[
            styles.tabButton,
            activeTab === 'My QRs' && styles.activeTabButton,
          ]}
          onPress={() => setActiveTab('My QRs')}
        >
          <Text style={[styles.tabText, activeTab === 'My QRs' && styles.activeTabText]}>My QRs</Text>
        </TouchableOpacity>
      </View>
      
      {activeTab === 'Notifications' && (
        <View style={styles.centeredContainer}>
          <Text style={styles.placeholderText}>No Notifications Found.</Text>
        </View>
      )}
      {activeTab === 'Activities' && (
        <View style={styles.centeredContainer}>
          <Text style={styles.placeholderText}>No Activities Found.</Text>
        </View>
      )}
      {activeTab === 'My QRs' && (
        <View style={styles.centeredContainer}>
          <Text style={styles.placeholderText}>No QR Codes Found.</Text>
        </View>
      )}
      
      <TouchableOpacity style={styles.backButton} onPress={() => router.push("/businesshomepage")}>
        <Text style={styles.backButtonText}>Back to Home</Text>
      </TouchableOpacity>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  header: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    backgroundColor: '#f5f5f5',
    paddingVertical: 25,
    borderBottomWidth: 3,
    borderBottomColor: '#ddd',
  },
  tabButton: {
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 8,
  },
  activeTabButton: {
    backgroundColor: '#007bff',
  },
  tabText: {
    fontSize: 16,
    color: '#000',
    textAlign: 'center',
  },
  activeTabText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  centeredContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  placeholderText: {
    fontSize: 18,
    color: '#888',
  },
  backButton: {
    backgroundColor: '#007bff',
    padding: 15,
    margin: 20,
    borderRadius: 8,
    alignItems: 'center',
  },
  backButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default NotificationPage;
