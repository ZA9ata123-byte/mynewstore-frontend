'use client';

import { useState, FormEvent } from 'react';
import { useRouter } from 'next/navigation';
import { adminLogin } from '@/services/adminAuthService';
import { AxiosError } from 'axios';
import Link from 'next/link';

export default function AdminLoginPage() {
    const router = useRouter();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState<string | null>(null);
    const [isLoading, setIsLoading] = useState(false);

    const handleSubmit = async (e: FormEvent) => {
        e.preventDefault();
        setIsLoading(true);
        setError(null);

        try {
            const response = await adminLogin({ email, password });
            if (response.is_admin) {
                router.push('/dashboard');
            } else {
                setError('ليس لديك صلاحيات الوصول.');
            }
        } catch (err) {
            const axiosError = err as AxiosError<{ message?: string }>;
            if (axiosError.response?.data?.message) {
                setError(axiosError.response.data.message);
            } else if (err instanceof Error) {
                setError(err.message);
            } else {
                setError('فشل تسجيل الدخول. يرجى التحقق من بياناتك.');
            }
            console.error(err);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="flex items-center justify-center min-h-screen bg-gray-100">
            <div className="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
                <h1 className="text-2xl font-bold text-center text-gray-900">تسجيل دخول المدير</h1>
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                            البريد الإلكتروني
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autoComplete="email"
                            required
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                        />
                    </div>
                    <div>
                        <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                            كلمة المرور
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autoComplete="current-password"
                            required
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            className="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                        />
                    </div>
                    {error && <p className="text-sm text-center text-red-600">{error}</p>}
                    <div>
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
                        >
                            {isLoading ? 'جاري الدخول...' : 'تسجيل الدخول'}
                        </button>
                    </div>
                </form>
                <div className="text-sm text-center">
                    {/* --- هنا تم الإصلاح --- */}
                    <Link href="/" className="font-medium text-indigo-600 hover:text-indigo-500">
                        العودة إلى المتجر
                    </Link>
                </div>
            </div>
        </div>
    );
}