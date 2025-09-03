// Path: src/app/(admin)/layout.tsx

import { AuthProvider } from "@/contexts/AuthContext";
import "../globals.css"; // <-- مهم جداً باش يطبق الستايل العام

export default function AdminRootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="ar" dir="rtl">
      <body>
        <AuthProvider>
          {children}
        </AuthProvider>
      </body>
    </html>
  );
}