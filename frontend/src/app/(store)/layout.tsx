// Path: src/app/(store)/layout.tsx

import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "../globals.css";
import { CartProvider } from "@/contexts/CartContext";
import { AuthProvider } from "@/contexts/AuthContext"; // <-- الخطوة 1: استيراد مزود المصادقة
import Navbar from "@/components/Navbar";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "متجري الجديد",
  description: "أفضل متجر إلكتروني على الإطلاق",
};

export default function StoreLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ar" dir="rtl">
      <body className={inter.className}>
        <AuthProvider> {/* <-- الخطوة 2: نضع AuthProvider ليحتضن كل شيء */}
          <CartProvider>
            <Navbar />
            <main className="container mx-auto px-4 py-8">
              {children}
            </main>
          </CartProvider>
        </AuthProvider> {/* <-- نهاية AuthProvider */}
      </body>
    </html>
  );
}