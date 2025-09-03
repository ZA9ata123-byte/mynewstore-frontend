// Path: src/app/(store)/reset-password/[token]/page.tsx

"use client";

import { useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { resetPassword } from '@/services/authService';
import { AxiosError } from 'axios';
// import Link from 'next/link'; <-- تم حذف هذا السطر لأنه غير مستخدم

export default function ResetPasswordPage() {
  const router = useRouter();
  const params = useParams();
  const token = params.token as string;

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [message, setMessage] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setMessage(null);

    if (password !== passwordConfirmation) {
      setError("كلمتا المرور غير متطابقتين.");
      setLoading(false);
      return;
    }

    try {
      const response = await resetPassword({
        token,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      setMessage(response.message + ". سيتم تحويلك لصفحة الدخول...");
      setTimeout(() => {
        router.push('/login');
      }, 3000);
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
        <h1 className="text-2xl font-bold text-center text-gray-900">إعادة تعيين كلمة المرور</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
            <input id="email" type="email" required value={email} onChange={(e) => setEmail(e.target.value)} className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"/>
          </div>
          <div>
            <label htmlFor="password" className="block text-sm font-medium text-gray-700">كلمة المرور الجديدة</label>
            <input id="password" type="password" required value={password} onChange={(e) => setPassword(e.target.value)} className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"/>
          </div>
          <div>
            <label htmlFor="passwordConfirmation" className="block text-sm font-medium text-gray-700">تأكيد كلمة المرور الجديدة</label>
            <input id="passwordConfirmation" type="password" required value={passwordConfirmation} onChange={(e) => setPasswordConfirmation(e.target.value)} className="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"/>
          </div>

          {message && <p className="text-sm text-center text-green-600 bg-green-50 p-3 rounded-md">{message}</p>}
          {error && <p className="text-sm text-center text-red-600 bg-red-50 p-3 rounded-md">{error}</p>}

          <div>
            <button type="submit" disabled={loading || !!message} className="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:bg-gray-400">
              {loading ? 'جاري التعيين...' : 'إعادة تعيين كلمة المرور'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}