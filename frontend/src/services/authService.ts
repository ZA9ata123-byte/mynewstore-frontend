// Path: src/services/authService.ts

import api from '@/lib/axiosConfig';

// (Interfaces remain the same)
interface User { id: number; name: string; email: string; }
interface AuthResponse { token: string; user?: User; }
interface RegisterData { name: string; email: string; password: string; password_confirmation: string; }
interface LoginCredentials { email: string; password: string; }
interface UpdateUserData { name: string; email: string; password?: string; password_confirmation?: string; }

// --- New Interface ---
interface ResetPasswordData {
  email: string;
  token: string;
  password: string;
  password_confirmation: string;
}

export const loginUser = async (credentials: LoginCredentials): Promise<AuthResponse> => {
  const response = await api.post('/login', credentials);
  return response.data;
};

export const registerUser = async (userData: RegisterData): Promise<AuthResponse> => {
  const response = await api.post('/register', userData);
  return response.data;
};

export const updateUser = async (userData: UpdateUserData): Promise<User> => {
  const response = await api.put('/user/update', userData);
  return response.data;
};

// --- New Function 1 ---
export const forgotPassword = async (email: string): Promise<{ message: string }> => {
  const response = await api.post('/forgot-password', { email });
  return response.data;
};

// --- New Function 2 ---
export const resetPassword = async (data: ResetPasswordData): Promise<{ message: string }> => {
  const response = await api.post('/reset-password', data);
  return response.data;
};