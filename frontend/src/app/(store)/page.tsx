import { getProducts } from "@/services/productService";
import ProductCard from "@/components/ProductCard";

export default async function HomePage() {
  const products = await getProducts();

  return (
    <section>
      <h1 className="text-3xl font-bold text-center mb-10">أحدث المنتجات</h1>
      
      {products && products.length > 0 ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
          {products.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      ) : (
        <p className="text-center text-gray-500 mt-12">
          لا توجد منتجات متاحة في الوقت الحالي. يرجى إضافتها من لوحة التحكم.
        </p>
      )}
    </section>
  );
}