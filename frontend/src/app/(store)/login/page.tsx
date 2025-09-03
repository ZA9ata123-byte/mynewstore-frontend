// Path: src/app/(store)/login/page.tsx

"use client";

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import { loginUser } from '@/services/authService';
import { AxiosError } from 'axios';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setLoading(true);

    try {
      const data = await loginUser({ email, password });
      login(data.token, data.user);
      router.push('/');
    } catch (err) {
      let errorMessage = "فشل تسجيل الدخول. يرجى التحقق من بياناتك.";
      if (err instanceof AxiosError && err.response) {
        errorMessage = err.response.data.message || errorMessage;
      }
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex justify-center items-center py-12">
      <div className="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h1 className="text-2xl font-bold text-center text-gray-900">تسجيل الدخول</h1>
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
            <input id="email" type="email" required value={email} onChange={(e) => setEmail(e.target.value)} className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"/>
          </div>
          <div>
            {/* --- هنا قمنا بإزالة الرابط --- */}
            <label htmlFor="password" className="block text-sm font-medium text-gray-700">كلمة المرور</label>
            <input id="password" type="password" required value={password} onChange={(e) => setPassword(e.target.value)} className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"/>
          </div>
          {error && <p className="text-sm text-center text-red-600">{error}</p>}
          <div>
            <button type="submit" disabled={loading} className="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:bg-gray-400">
              {loading ? 'جاري التحقق...' : 'دخول'}
            </button>
          </div>
        </form>
        {/* --- وهنا قمنا بإضافة الرابط في مكانه الصحيح --- */}
        <div className="text-sm text-center text-gray-600 space-y-2">
            <p>
                ليس لديك حساب؟{' '}
                <Link href="/register" className="font-medium text-blue-600 hover:underline">أنشئ حساباً جديداً</Link>
            </p>
            <p>
                <Link href="/forgot-password" className="font-medium text-blue-600 hover:underline">
                    نسيت كلمة السر؟
                </Link>
            </p>
        </div>
      </div>
    </div>
  );
}