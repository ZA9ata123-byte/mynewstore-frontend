// Path: src/contexts/AuthContext.tsx

"use client";

import React, { createContext, useState, useContext, ReactNode, useEffect, useCallback } from 'react';
import api from '@/lib/axiosConfig';

// تعريف شكل بيانات المستخدم
interface User {
  id: number;
  name: string;
  email: string;
}

// تعريف شكل السياق
interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  login: (token: string, userData?: User) => void; // سنستقبل بيانات المستخدم مباشرة
  logout: () => void;
  isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  // دالة لجلب المستخدم بناءً على التوكن
  const fetchUser = useCallback(async () => {
    const token = localStorage.getItem('user_token');
    if (!token) {
      setIsLoading(false);
      return;
    }
    try {
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      const response = await api.get<User>('/user');
      setUser(response.data);
    } catch (error) {
      console.error("Token non valide, déconnexion...", error);
      logout(); // إذا كان التوكن غير صالح، قم بالخروج
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchUser();
  }, [fetchUser]);

  const login = (token: string, userData?: User) => {
    localStorage.setItem('user_token', token);
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    // إذا كانت بيانات المستخدم متوفرة من صفحة الدخول/التسجيل، استخدمها مباشرة
    // هذا يحل مشكلة طلب البيانات فوراً بتوكن جديد
    if (userData) {
      setUser(userData);
    } else {
      // إذا لم تكن متوفرة، اطلبها من الخادم
      fetchUser();
    }
  };

  const logout = () => {
    localStorage.removeItem('user_token');
    delete api.defaults.headers.common['Authorization'];
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, isAuthenticated: !!user, login, logout, isLoading }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};