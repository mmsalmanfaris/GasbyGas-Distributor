import { DarkTheme, DefaultTheme, ThemeProvider } from '@react-navigation/native';
import { useFonts } from 'expo-font';
import { Stack } from 'expo-router';
import * as SplashScreen from 'expo-splash-screen';
import { StatusBar } from 'expo-status-bar';
import { useEffect } from 'react';
import 'react-native-reanimated';

import { useColorScheme } from '@/hooks/useColorScheme';
import React from 'react';

// Prevent the splash screen from auto-hiding before asset loading is complete.
SplashScreen.preventAutoHideAsync();

export default function RootLayout() {
  const colorScheme = useColorScheme();
  const [loaded] = useFonts({
    SpaceMono: require('../assets/fonts/SpaceMono-Regular.ttf'),
  });

  useEffect(() => {
    if (loaded) {
      SplashScreen.hideAsync();
    }
  }, [loaded]);

  if (!loaded) {
    return null;
  }

  return (
    <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
      <Stack>
        
        <Stack.Screen name="activity" options={{ headerShown: false }} />
        <Stack.Screen name="bsuccessmessage" options={{ headerShown: false }} />
        <Stack.Screen name="brequest" options={{ headerShown: false }} />
        <Stack.Screen name="businessotp" options={{ headerShown: false }} />
        <Stack.Screen name="businesshomepage" options={{ headerShown: false }} />
        <Stack.Screen name="businessprofile" options={{ headerShown: false }} />
        <Stack.Screen name="requestpage" options={{ headerShown: false }} />
        <Stack.Screen name="notification" options={{ headerShown: false }} />
        <Stack.Screen name="profile" options={{ headerShown: false }} />
        <Stack.Screen name="successmessage" options={{ headerShown: false }} />
        <Stack.Screen name="otp" options={{ headerShown: false }} />
        <Stack.Screen name="homepage" options={{ headerShown: false }} />
        <Stack.Screen name="customerlogin" options={{ headerShown: false }} />
        <Stack.Screen name="customerregister" options={{ headerShown: false }} />
        <Stack.Screen name="businessregistration" options={{ headerShown: false }} />
        <Stack.Screen name="index" options={{ headerShown: false }} />
        <Stack.Screen name="login" options={{ headerShown: false }} />
        <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
        <Stack.Screen name="+not-found" />
      </Stack>
      <StatusBar style="auto" />
    </ThemeProvider>
  );
}
