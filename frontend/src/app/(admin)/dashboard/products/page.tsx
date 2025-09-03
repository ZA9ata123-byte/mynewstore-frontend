'use client';

import { useEffect, useState } from "react";
import Link from "next/link";
import { getAdminProducts, Product, Paginator, deleteAdminProduct } from "@/services/adminProductService";

export default function ProductsPage() {
    const [paginator, setPaginator] = useState<Paginator<Product> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                setIsLoading(true);
                setError(null);
                const paginatorResponse = await getAdminProducts();
                setPaginator(paginatorResponse);
            } catch (err: unknown) {
                if (err instanceof Error) {
                    setError(err.message);
                } else {
                    setError("حدث خطأ غير متوقع أثناء جلب المنتجات.");
                }
                console.error(err);
            } finally {
                setIsLoading(false);
            }
        };

        fetchProducts();
    }, []);

    const handleDelete = async (productId: number) => {
        if (window.confirm('هل أنت متأكد من أنك تريد حذف هذا المنتج؟')) {
            try {
                await deleteAdminProduct(productId);
                setPaginator(prev => prev ? { ...prev, data: prev.data.filter(p => p.id !== productId), total: prev.total - 1 } : null);
                alert('تم حذف المنتج بنجاح.');
            } catch (error) {
                alert('فشل حذف المنتج.');
                console.error(error);
            }
        }
    };

    if (isLoading) return <div className="p-10 text-center text-gray-500">جاري تحميل المنتجات...</div>;
    if (error) return <div className="p-10 text-center text-red-500 bg-red-50 rounded-lg">{error}</div>;

    return (
        <div className="container mx-auto">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-800">المنتجات</h1>
                <Link href="/dashboard/products/new" className="bg-blue-600 text-white py-2 px-4 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200">
                    + إضافة منتج
                </Link>
            </div>

            <div className="bg-white shadow-md rounded-lg overflow-x-auto">
                <table className="min-w-full">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المخزون</th>
                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200">
                        {paginator && paginator.data.length > 0 ? (
                            paginator.data.map((product) => (
                                <tr key={product.id} className="hover:bg-gray-50 transition-colors duration-150">
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm font-medium text-gray-900">{product.name}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-600">{product.price_range}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${product.total_stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                            {product.total_stock > 0 ? `متوفر (${product.total_stock})` : 'نفذ المخزون'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link href={`/dashboard/products/${product.id}`} className="text-indigo-600 hover:text-indigo-900">
                                            تعديل
                                        </Link>
                                        <button onClick={() => handleDelete(product.id)} className="text-red-600 hover:text-red-900 ml-4">
                                            حذف
                                        </button>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan={4} className="px-6 py-10 text-center text-gray-500">
                                    لم يتم العثور على أي منتجات. قم بإضافة منتج جديد.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}