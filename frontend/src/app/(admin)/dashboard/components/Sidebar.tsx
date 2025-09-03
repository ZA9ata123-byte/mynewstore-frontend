'use client';

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { logoutAdmin } from '@/services/adminAuthService';

export default function Sidebar() {
    const pathname = usePathname();
    const router = useRouter();

    const isActive = (path: string) => pathname.startsWith(path);

    const handleLogout = () => {
        logoutAdmin();
        router.push('/dashboard/components/login');
    };

    return (
        <div className="flex flex-col w-64 bg-gray-800 text-white">
            <div className="flex items-center justify-center h-16 border-b border-gray-700">
                <span className="text-xl font-bold">لوحة التحكم</span>
            </div>
            <nav className="flex-1 px-2 py-4 space-y-2">
                <Link
                    href="/dashboard"
                    className={`flex items-center px-4 py-2 rounded-md ${pathname === '/dashboard' ? 'bg-gray-700' : 'hover:bg-gray-700'}`}
                >
                    <span>الرئيسية</span>
                </Link>
                
                {/* --- هذا هو السطر المهم والمصحح --- */}
                <Link
                    href="/dashboard/products"
                    className={`flex items-center px-4 py-2 rounded-md ${isActive('/dashboard/products') ? 'bg-gray-700' : 'hover:bg-gray-700'}`}
                >
                    <span>المنتجات</span>
                </Link>
                
                {/* يمكنك إضافة روابط أخرى هنا */}
            </nav>
            <div className="px-2 py-4 border-t border-gray-700">
                <button
                    onClick={handleLogout}
                    className="w-full flex items-center px-4 py-2 rounded-md hover:bg-red-600 text-left"
                >
                    <span>تسجيل الخروج</span>
                </button>
            </div>
        </div>
    );
}