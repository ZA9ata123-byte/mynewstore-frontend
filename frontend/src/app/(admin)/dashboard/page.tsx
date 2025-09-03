'use client';

// In the future, we will import functions to get real data
// import { getDashboardStats } from '@/services/adminDashboardService';

// Temporary data for statistics
const stats = [
  { name: 'إجمالي المبيعات', stat: '0 د.م.' },
  { name: 'الطلبات الجديدة', stat: '0' },
  { name: 'العملاء الجدد', stat: '0' },
];

export default function DashboardHomePage() {
  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-900 mb-8">لوحة التحكم الرئيسية</h1>

      {/* Statistics Section */}
      <div>
        <h3 className="text-lg font-medium leading-6 text-gray-900">نظرة عامة سريعة</h3>
        <dl className="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {stats.map((item) => (
            <div key={item.name} className="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-8 shadow sm:px-6 sm:pt-6">
              <dt>
                <p className="truncate text-sm font-medium text-gray-500">{item.name}</p>
              </dt>
              <dd className="mt-1 flex items-baseline justify-between">
                <div className="flex items-baseline text-2xl font-semibold text-indigo-600">
                  {item.stat}
                </div>
              </dd>
            </div>
          ))}
        </dl>
      </div>

      {/* Other sections to be added later */}
      <div className="mt-12">
        <h3 className="text-lg font-medium leading-6 text-gray-900">آخر الطلبات</h3>
        <div className="mt-4 bg-white p-6 rounded-lg shadow">
          <p className="text-center text-gray-500">سيتم عرض قائمة بآخر 5 طلبات هنا...</p>
        </div>
      </div>
    </div>
  );
}