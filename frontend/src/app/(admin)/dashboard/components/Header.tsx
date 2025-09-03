// File: src/app/dashboard/components/Header.tsx
'use client';

import { useAuth } from '@/contexts/AuthContext';
import { usePathname } from 'next/navigation';

export default function Header() {
  const { user, logout } = useAuth();
  const pathname = usePathname();
  
  // Get the title from the last part of the URL
  const title = pathname.split('/').pop() || 'dashboard';

  return (
    <header className="bg-white shadow-sm p-4 flex justify-between items-center">
      <div>
        <h1 className="text-xl font-semibold text-gray-800 capitalize">{title}</h1>
      </div>
      <div className="flex items-center gap-4">
        <span className="text-sm text-gray-600">Welcome, {user?.name}</span>
        <button 
          onClick={logout}
          className="px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600 transition-colors"
        >
          Logout
        </button>
      </div>
    </header>
  );
}