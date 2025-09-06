'use client';
import { useAuth } from "@/contexts/AuthContext";

export default function DashboardPage() {
    const { user, logout } = useAuth();

    return (
        <div className="flex flex-col items-center justify-center h-screen">
            <h1 className="text-4xl font-bold mb-4">
                Welcome to the Dashboard, {user?.name}!
            </h1>
            <p className="text-lg mb-8">
                Everything is working correctly.
            </p>
            <button
                onClick={logout}
                className="px-6 py-3 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600 transition-colors"
            >
                Logout
            </button>
        </div>
    );
}