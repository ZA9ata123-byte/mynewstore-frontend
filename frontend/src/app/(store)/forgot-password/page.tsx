// Path: src/app/(store)/forgot-password/page.tsx

"use client";

import { useState } from 'react';
import Link from 'next/link';
import { forgotPassword } from '@/services/authService';
import { AxiosError } from 'axios';

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setMessage(null);

    try {
      const response = await forgotPassword(email);
      setMessage(response.message);
    } catch (err) {
      let errorMessage = "حدث خطأ غير متوقع.";
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
        <h1 className="text-2xl font-bold text-center text-gray-900">نسيت كلمة السر؟</h1>
        <p className="text-center text-sm text-gray-600">
          لا مشكلة. فقط أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة مرورك.
        </p>
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
              البريد الإلكتروني
            </label>
            <input
              id="email" type="email" required value={email} onChange={(e) => setEmail(e.target.value)}
              className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          {message && <p className="text-sm text-center text-green-600 bg-green-50 p-3 rounded-md">{message}</p>}
          {error && <p className="text-sm text-center text-red-600 bg-red-50 p-3 rounded-md">{error}</p>}

          <div>
            <button type="submit" disabled={loading || !!message} className="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:bg-gray-400">
              {loading ? 'جاري الإرسال...' : 'إرسال رابط إعادة التعيين'}
            </button>
          </div>
        </form>
         <p className="text-sm text-center text-gray-600">
            تذكرت كلمة السر؟{' '}
          <Link href="/login" className="font-medium text-blue-600 hover:underline">
            العودة لتسجيل الدخول
          </Link>
        </p>
      </div>
    </div>
  );
}