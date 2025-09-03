// Path: src/components/Navbar.tsx

"use client";

import Link from 'next/link';
import { useCart } from '@/contexts/CartContext'; // استيراد Hook السلة
import { useAuth } from '@/contexts/AuthContext'; // الخطوة 1: استيراد Hook المصادقة

export default function Navbar() {
  const { itemCount } = useCart();
  // الخطوة 2: الوصول لحالة المستخدم ودوال التحكم
  const { isAuthenticated, user, logout, isLoading } = useAuth(); 

  return (
    <header className="bg-white shadow-md sticky top-0 z-50">
      <nav className="container mx-auto px-6 py-4 flex justify-between items-center">
        <Link href="/" className="text-2xl font-bold text-gray-800">
          متجري
        </Link>
        
        <div className="flex items-center space-x-6 md:space-x-8">
          <Link href="/" className="text-gray-600 hover:text-blue-500">
            الرئيسية
          </Link>
          
          {/* --- المنطق الذكي لعرض الروابط المناسبة --- */}
          {isLoading ? (
            <div className="text-sm text-gray-500">...</div> // عرض مؤشر تحميل بسيط
          ) : isAuthenticated ? (
            <>
              {/* إذا كان المستخدم مسجلاً دخوله */}
              <Link href="/profile" className="text-gray-600 hover:text-blue-500">
                أهلاً، {user?.name.split(' ')[0]} {/* عرض الاسم الأول فقط */}
              </Link>
              <button onClick={logout} className="text-sm text-red-500 hover:text-red-700">
                تسجيل الخروج
              </button>
            </>
          ) : (
            <>
              {/* إذا كان المستخدم زائراً */}
              <Link href="/login" className="text-gray-600 hover:text-blue-500">
                تسجيل الدخول
              </Link>
              <Link href="/register" className="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-blue-600">
                إنشاء حساب
              </Link>
            </>
          )}
          {/* --- نهاية المنطق الذكي --- */}

          <Link href="/cart" className="relative flex items-center text-gray-600 hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            {itemCount > 0 && (
              <span className="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {itemCount}
              </span>
            )}
          </Link>
        </div>
      </nav>
    </header>
  );
}