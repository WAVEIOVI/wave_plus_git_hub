export default [
  { heading: "TEST" },
  {
    title: "TEST",
    icon: { icon: "tabler-shopping-cart" },
    children: [
      {
        title: "Home",
        to: { name: "index" },
        action: "read",
        subject: "Auth",
      },
      {
        title: "Super Admin Dashboard",
        to: { name: "dashboard-super-admin-dashboard" },
        action: "manage",
        subject: "all",
      },
      {
        title: "Admin Dashboard",
        to: { name: "dashboard-admin-dashboard" },
        action: "manage",
        subject: ["ClientDashboard", "AdminDashboard"],
      },
      {
        title: "Operations Manager Dashboard",
        to: { name: "dashboard-operations-manager-dashboard" },
        action: "manage",
        subject: "OperationsManagerDashboard",
      },
      {
        title: "Finance Manager Dashboard",
        to: { name: "dashboard-finance-manager-dashboard" },
        action: "manage",
        subject: "FinanceManagerDashboard",
      },
      {
        title: "Technician Dashboard",
        to: { name: "dashboard-technician-dashboard" },
        action: "manage",
        subject: "TechnicianDashboard",
      },
      {
        title: "Client Dashboard",
        to: { name: "dashboard-client-dashboard" },
        action: "manage",
        subject: "ClientDashboard",
      },
      {
        title: "Client Dashboard Test",
        to: { name: "dashboard-client-dashboard-test" },
        action: "manage",
        subject: "ClientDashboard",
      },
    ],
  },
]
