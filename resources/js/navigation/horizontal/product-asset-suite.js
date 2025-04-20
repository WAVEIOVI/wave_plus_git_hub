export default [
  {
    title: "Product Asset Suite",
    icon: { icon: "tabler-layout-grid-add" },
    children: [
      {
        title: "Products Catalog",

        to: { name: "product-asset-suite-product-catalog-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
      {
        title: "Eq Servicings Catalog",

        to: { name: "product-asset-suite-equipment-servicing-catalog-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
      {
        title: "Consumables Catalog",

        to: { name: "product-asset-suite-consumable-catalog-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
      {
        title: "Equipment Blueprint",

        to: { name: "product-asset-suite-equipment-blueprint-catalog-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
      {
        title: "Category Framework",
        to: { name: "product-asset-suite-category-framework-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
      {
        title: "Core Settings",
        to: { name: "product-asset-suite-core-settings-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "ProductAssetSuite",
      },
    ],
  },
]
