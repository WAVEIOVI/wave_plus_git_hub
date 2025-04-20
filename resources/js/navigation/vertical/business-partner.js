export default [
  { heading: "Business Partner" },
  {
    title: "Business Partner",
    icon: { icon: "tabler-shopping-cart" },
    children: [
      {
        title: "Dashboard",
        to: { name: "business-partner-dashboard" },
        action: "manage",
        subject: "BusinessPartner",
      },
      {
        title: "Clients",
        to: { name: "business-partner-clients-list" },
        action: "manage",
        subject: "BusinessPartner",
      },
      {
        title: "Suppliers",
        to: { name: "business-partner-suppliers-list" },
        action: "manage",
        subject: "BusinessPartner",
      },
    ],
  },
]
