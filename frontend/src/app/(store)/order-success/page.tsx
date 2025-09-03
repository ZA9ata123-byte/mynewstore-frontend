// Path: src/app/(store)/order-success/page.tsx

"use client";

import Link from 'next/link';
import { useEffect } from 'react';

export default function OrderSuccessPage() {
  useEffect(() => {
    console.log("Order placed successfully! Ready for analytics tracking.");
  }, []);

  return (
    <div className="container mx-auto px-4 py-16 text-center">
      <div className="max-w-lg mx-auto">
        <div className="mx-auto bg-green-100 rounded-full h-24 w-24 flex items-center justify-center">
          <svg className="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
          </svg>
        </div>
        
        <h1 className="mt-6 text-3xl font-bold text-gray-900">
          شكراً لك! لقد تم استلام طلبك بنجاح.
        </h1>
        
        {/* --- هنا تم التصحيح --- */}
        <p className="mt-4 text-lg text-gray-600">
          سنتواصل معك قريباً لتأكيد تفاصيل الشحن. يمكنك متابعة حالة طلبك من خلال صفحة «حسابي».
        </p>
        
        <div className="mt-10 flex flex-col sm:flex-row justify-center gap-4">
          <Link href="/" className="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
            العودة للصفحة الرئيسية
          </Link>
          <Link href="/profile" className="px-8 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
            الذهاب إلى حسابي
          </Link>
        </div>
      </div>
    </div>
  );
}