export default [
  {
    title: "Business Partner",
    icon: { icon: "tabler-layout-grid-add" },
    children: [
      {
        title: "Dashboard",
        to: { name: "business-partner-dashboard" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "BusinessPartner",
      },
      {
        title: "Clients",
        to: { name: "business-partner-clients-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "BusinessPartner",
      },
      {
        title: "Suppliers",
        to: { name: "business-partner-suppliers-list" },
        icon: { icon: "tabler-dashboard" },
        action: "manage",
        subject: "BusinessPartner",
      },
    ],
  },
]
