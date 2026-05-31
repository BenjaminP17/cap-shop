<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Enum\BadgeType;
use App\Enum\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const PRODUCTS = [
        // ── Drops récents (droppedAt récent, badgé) ──────────────────────────
        [
            'name'        => 'Bleu Cobalt 6-Panel',
            'brand'       => 'CapShop Studio',
            'price'       => 49.0,
            'category'    => Category::SNAPBACK,
            'badge'       => BadgeType::NEW_DROP,
            'stock'       => 48,
            'soldCount'   => 12,
            'bestseller'  => false,
            'daysAgo'     => 4,
            'description' => 'Snapback 6 panneaux en twill de coton épais, bord plat renforcé. Colorway Klein exclusif.',
            'image'       => 'https://images.unsplash.com/photo-1556306535-0f09a537f0a3?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Encre Fitted Pro',
            'brand'       => 'Atelier Marseille',
            'price'       => 42.0,
            'category'    => Category::FITTED,
            'badge'       => BadgeType::NEW,
            'stock'       => 30,
            'soldCount'   => 27,
            'bestseller'  => false,
            'daysAgo'     => 7,
            'description' => 'Fitted structuré 59Fifty-style, visière courbée cousue main, pastille brodée au dos.',
            'image'       => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Mousse Dad-Cap',
            'brand'       => 'Washed & Co',
            'price'       => 29.0,
            'category'    => Category::DAD_CAP,
            'badge'       => BadgeType::JUST_IN,
            'stock'       => 75,
            'soldCount'   => 44,
            'bestseller'  => false,
            'daysAgo'     => 9,
            'description' => 'Dad cap en coton washed délavé, non structuré, fermeture à boucle métal dorée.',
            'image'       => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Trucker Lilas Mesh',
            'brand'       => 'Snap Market',
            'price'       => 34.0,
            'category'    => Category::TRUCKER,
            'badge'       => BadgeType::NEW,
            'stock'       => 55,
            'soldCount'   => 19,
            'bestseller'  => false,
            'daysAgo'     => 11,
            'description' => 'Trucker front foam, dos mesh respirant colorway lilas pastel. Fermeture snapback PVC.',
            'image'       => 'https://images.unsplash.com/photo-1620327467532-6ebaca6273ed?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Bucket Coral Wave',
            'brand'       => 'Plage·Co',
            'price'       => 38.0,
            'category'    => Category::BUCKET,
            'badge'       => BadgeType::LOW_STOCK,
            'stock'       => 6,
            'soldCount'   => 89,
            'bestseller'  => false,
            'daysAgo'     => 13,
            'description' => 'Bucket hat réversible 100% coton — face coral, face crème. Bord tombant mid-size.',
            'image'       => 'https://images.unsplash.com/photo-1624518681328-bc59eefa1ce4?w=600&h=600&fit=crop&q=80',
        ],

        // ── Bestsellers (soldCount élevé, isBestseller: true) ─────────────────
        [
            'name'        => 'Klein Hit Snapback',
            'brand'       => 'CapShop Studio',
            'price'       => 39.0,
            'category'    => Category::SNAPBACK,
            'badge'       => null,
            'stock'       => 120,
            'soldCount'   => 482,
            'bestseller'  => true,
            'daysAgo'     => 42,
            'description' => 'Le bestseller absolu. Snapback en twill bleu Klein, broderie logo contrasté blanc.',
            'image'       => 'https://images.unsplash.com/photo-1570748092285-6203601e4bcb?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Coral Run Fitted',
            'brand'       => 'Atelier 47',
            'price'       => 42.0,
            'category'    => Category::FITTED,
            'badge'       => null,
            'stock'       => 80,
            'soldCount'   => 318,
            'bestseller'  => true,
            'daysAgo'     => 58,
            'description' => 'Fitted structuré coral vif, undervisor vert néon. Édition running limitée.',
            'image'       => 'https://images.unsplash.com/photo-1591818343198-4ff334074580?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Sauge Washed Dad',
            'brand'       => 'Maison Flex',
            'price'       => 29.0,
            'category'    => Category::DAD_CAP,
            'badge'       => null,
            'stock'       => 95,
            'soldCount'   => 267,
            'bestseller'  => true,
            'daysAgo'     => 70,
            'description' => 'Dad cap couleur sauge délavée, coton bio 320g, boucle réglable ton sur ton.',
            'image'       => 'https://images.unsplash.com/photo-1534215754734-18e55d13e346?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Soleil Bucket Hat',
            'brand'       => 'Plage·Co',
            'price'       => 35.0,
            'category'    => Category::BUCKET,
            'badge'       => null,
            'stock'       => 110,
            'soldCount'   => 201,
            'bestseller'  => true,
            'daysAgo'     => 85,
            'description' => 'Bucket jaune soleil en canvas 100% coton, logo brodé discret sur le côté.',
            'image'       => 'https://images.unsplash.com/photo-1648422204972-4278784a9863?w=600&h=600&fit=crop&q=80',
        ],

        // ── Catalogue étendu ──────────────────────────────────────────────────
        [
            'name'        => 'Onyx 5-Panel Strapback',
            'brand'       => 'CapShop Studio',
            'price'       => 36.0,
            'category'    => Category::FIVE_PANEL,
            'badge'       => BadgeType::NEW,
            'stock'       => 40,
            'soldCount'   => 33,
            'bestseller'  => false,
            'daysAgo'     => 16,
            'description' => '5-Panel en nylon ripstop noir onyx, doublure satin, fermeture velcro discret.',
            'image'       => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Vintage Wash Trucker',
            'brand'       => 'Old School Caps',
            'price'       => 32.0,
            'category'    => Category::TRUCKER,
            'badge'       => BadgeType::JUST_IN,
            'stock'       => 28,
            'soldCount'   => 61,
            'bestseller'  => false,
            'daysAgo'     => 20,
            'description' => 'Trucker old-school effet vieilli, foam avant imprimé, mesh noir vintage.',
            'image'       => 'https://images.unsplash.com/photo-1620231109648-302d034cb29b?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Linen Dad-Cap Crème',
            'brand'       => 'Maison Flex',
            'price'       => 31.0,
            'category'    => Category::DAD_CAP,
            'badge'       => null,
            'stock'       => 60,
            'soldCount'   => 104,
            'bestseller'  => false,
            'daysAgo'     => 35,
            'description' => 'Dad cap en lin naturel non structuré, teinture naturelle crème ivoire. Minimal & rare.',
            'image'       => 'https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Paille Summer Bucket',
            'brand'       => 'Riviera Lab',
            'price'       => 33.0,
            'category'    => Category::BUCKET,
            'badge'       => null,
            'stock'       => 45,
            'soldCount'   => 77,
            'bestseller'  => false,
            'daysAgo'     => 28,
            'description' => 'Bucket en raphia naturel tressé, doublure coton, cordon réglable intérieur.',
            'image'       => 'https://images.unsplash.com/photo-1533827432537-70133748f5c8?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Mesh Classic Snapback',
            'brand'       => 'Street Grid',
            'price'       => 37.0,
            'category'    => Category::SNAPBACK,
            'badge'       => null,
            'stock'       => 85,
            'soldCount'   => 143,
            'bestseller'  => false,
            'daysAgo'     => 50,
            'description' => 'Snapback bicolore foam/mesh, broderie avant et patch arrière, fermeture plastique.',
            'image'       => 'https://images.unsplash.com/photo-1513346940221-6f673d962e97?w=600&h=600&fit=crop&q=80',
        ],
        [
            'name'        => 'Noir Mat Fitted',
            'brand'       => 'Atelier Marseille',
            'price'       => 45.0,
            'category'    => Category::FITTED,
            'badge'       => BadgeType::COLLAB,
            'stock'       => 22,
            'soldCount'   => 58,
            'bestseller'  => false,
            'daysAgo'     => 23,
            'description' => 'Fitted exclusif Atelier Marseille × CapShop — wool blend noir mat, undervisor coral.',
            'image'       => 'https://images.unsplash.com/photo-1607346256330-dee7af15f7c5?w=600&h=600&fit=crop&q=80',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRODUCTS as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setBrand($data['brand']);
            $product->setPriceFloat($data['price']);
            $product->setCategory($data['category']);
            $product->setBadge($data['badge']);
            $product->setStock($data['stock']);
            $product->setSoldCount($data['soldCount']);
            $product->setIsBestseller($data['bestseller']);
            $product->setIsActive(true);
            $product->setDescription($data['description']);
            $product->setImageUrl($data['image']);
            $product->setDroppedAt(
                new \DateTimeImmutable("-{$data['daysAgo']} days")
            );

            $manager->persist($product);
        }

        $manager->flush();
    }
}
