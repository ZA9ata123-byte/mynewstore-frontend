// app/layout.tsx

import './globals.css'; // ملف الستايل العام للتطبيق كامل

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="ar">
      <body>
        {/* Next.js غادي يحط هنا المحتوى ديال الملفات الأخرى، بما فيهم الـ layout ديال الأدمن */}
        {children}
      </body>
    </html>
  );
}