import i18n from "@/plugins/i18n"

export function getBpHeaders() {
  return [
    { title: i18n.global.t("Client"), key: "legal_name" },
    { title: i18n.global.t("Tier"), key: "client_tier" },
    { title: i18n.global.t("Industry"), key: "industry" },
    { title: i18n.global.t("Status"), key: "client_status" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getProductHeaders() {
  return [
    { title: i18n.global.t("Category"), key: "category" },
    { title: i18n.global.t("Product"), key: "name" },
    { title: i18n.global.t("Type"), key: "product_type" },
    { title: i18n.global.t("Price Excl Tax"), key: "base_price" },
    { title: i18n.global.t("Qty"), key: "stock_quantity" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getEqServicingHeaders() {
  return [
    { title: i18n.global.t("Category"), key: "category" },
    { title: i18n.global.t("Equipment Servicing"), key: "name" },
    { title: i18n.global.t("Type"), key: "eq_servicing_type" },
    { title: i18n.global.t("Price Excl Tax"), key: "base_price" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getEqBlueprintHeaders() {
  return [
    { title: i18n.global.t("Category"), key: "category" },
    { title: i18n.global.t("Equipment Blueprint"), key: "name" },
    { title: i18n.global.t("Description"), key: "description" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getConsumableHeaders() {
  return [
    { title: i18n.global.t("Category"), key: "category" },
    { title: i18n.global.t("Consumable"), key: "name" },
    { title: i18n.global.t("Type"), key: "consumable_type" },
    { title: i18n.global.t("Price Excl Tax"), key: "base_price" },
    { title: i18n.global.t("Qty"), key: "stock_quantity" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getCategoriesHeaders() {
  return [
    { title: i18n.global.t("Category"), key: "category_name" },
    { title: i18n.global.t("Description"), key: "category_description" },
    { title: i18n.global.t("Status"), key: "category_status" },
    { title: i18n.global.t("Creation Date"), key: "created_at" },
    { title: i18n.global.t("Actions"), key: "actions", sortable: false },
  ]
}

export function getCategoryStatus() {
  return [
    { title: i18n.global.t("Draft"), value: "draft" },
    { title: i18n.global.t("Active"), value: "active" },
    { title: i18n.global.t("Inactive"), value: "inactive" },
  ]
}

export function getBpTiers() {
  return [
    { title: i18n.global.t("Unclassified"), value: "unclassified" },
    { title: i18n.global.t("Individual"), value: "individual" },
    { title: i18n.global.t("Basic"), value: "basic" },
    { title: i18n.global.t("Enterprise"), value: "enterprise" },
    { title: i18n.global.t("Premium"), value: "premium" },
    { title: i18n.global.t("Group"), value: "group" },
  ]
}

export function getProductTypes() {
  return [
    { title: i18n.global.t("Physical"), value: "physical" },
    { title: i18n.global.t("Service"), value: "service" },
    { title: i18n.global.t("Digital"), value: "digital" },
  ]
}

export function getConsumableTypes() {
  return [
    { title: i18n.global.t("Agents & Disposables"), value: "agents and disposables" },
    { title: i18n.global.t("Replacement Parts"), value: "replacement parts" },
    { title: i18n.global.t("Safety Signage"), value: "safety signage" },
    { title: i18n.global.t("Accessories"), value: "accessories" },
  ]
}

export function getEqServicingTypes() {
  return [
    { title: i18n.global.t("Inspection & Testing"), value: "inspection and testing" },
    { title: i18n.global.t("Maintenance & Repair"), value: "maintenance and repair" },
    { title: i18n.global.t("Refill/Recharge"), value: "refill recharge" },
    { title: i18n.global.t("Calibration & Certification"), value: "calibration and certification" },
    { title: i18n.global.t("Installation/Decommissioning"), value: "installation decommissioning" },
  ]
}

export function getEquipmentFrequencies() {
  return [
    { title: i18n.global.t("After Each Use"), value: "after_use" },
    { title: i18n.global.t("Daily"), value: "daily" },
    { title: i18n.global.t("Weekly"), value: "weekly" },
    { title: i18n.global.t("Bi-Weekly"), value: "bi_weekly" },
    { title: i18n.global.t("Monthly"), value: "monthly" },
    { title: i18n.global.t("Quarterly"), value: "quarterly" },
    { title: i18n.global.t("Semi-Annually"), value: "semi_annually" },
    { title: i18n.global.t("Annually"), value: "annually" },
    { title: i18n.global.t("Biennially"), value: "biennially" },
    { title: i18n.global.t("Quinquennially"), value: "quinquennially" },
    { title: i18n.global.t("Every 10 Years"), value: "every_10_years" },
    { title: i18n.global.t("On Failure"), value: "on_failure" },
    { title: i18n.global.t("Condition-Based"), value: "condition_based" },
    { title: i18n.global.t("As Needed"), value: "as_needed" },
    { title: i18n.global.t("Per Manufacturer Spec"), value: "manufacturer_spec" },
  ]
}

export function getEquipmentFireClasses() {
  return [
    { title: i18n.global.t("Class A - Solid Materials (Wood, Paper, Textiles)"), value: "class_a" },
    { title: i18n.global.t("Class B - Liquid Fires (Petrol, Oil, Solvents)"), value: "class_b" },
    { title: i18n.global.t("Class C - Gas Fires (Propane, Methane, Natural Gas)"), value: "class_c" },
    { title: i18n.global.t("Class D - Metal Fires (Magnesium, Sodium, Potassium)"), value: "class_d" },
    { title: i18n.global.t("Class F - Cooking Fires (Oils & Fats)"), value: "class_f" },
    { title: i18n.global.t("Electrical Equipment Protection"), value: "electrical_risk" },
    { title: i18n.global.t("Combined Class A/B"), value: "class_ab" },
    { title: i18n.global.t("Combined Class B/C"), value: "class_bc" },
    { title: i18n.global.t("Multi-Risk (A/B/C/D/F)"), value: "multi_class" },
    { title: i18n.global.t("EN 54 Compliant Detection Systems"), value: "en54_systems" },
    { title: i18n.global.t("EN 13565-1 Foam Systems"), value: "foam_systems" },
    { title: i18n.global.t("EN 15004 Gas Suppression Systems"), value: "gas_suppression" },
  ]
}

export function getBpIndustries() {
  return [
    { title: i18n.global.t("Agriculture"), value: "agriculture" },
    { title: i18n.global.t("Automotive"), value: "automotive" },
    {
      title: i18n.global.t("Banking And Finance"),
      value: "banking and finance",
    },
    { title: i18n.global.t("Construction"), value: "construction" },
    { title: i18n.global.t("Education"), value: "education" },
    { title: i18n.global.t("Energy"), value: "energy" },
    { title: i18n.global.t("Healthcare"), value: "healthcare" },
    {
      title: i18n.global.t("Hospitality And Tourism"),
      value: "hospitality and tourism",
    },
    {
      title: i18n.global.t("Information Technology (IT)"),
      value: "information technology",
    },
    { title: i18n.global.t("Manufacturing"), value: "manufacturing" },
    { title: i18n.global.t("Real Estate"), value: "real estate" },
    { title: i18n.global.t("Retail"), value: "retail" },
    { title: i18n.global.t("Telecommunications"), value: "telecommunications" },
    {
      title: i18n.global.t("Transportation And Logistics"),
      value: "transportation and logistics",
    },
    { title: i18n.global.t("Utilities"), value: "utilities" },
    {
      title: i18n.global.t("Wholesale Retail Trade"),
      value: "wholesale retail trade",
    },
    {
      title: i18n.global.t("Media And Entertainment"),
      value: "media and entertainment",
    },
    {
      title: i18n.global.t("Legal And Professional Services"),
      value: "legal and professional services",
    },
    { title: i18n.global.t("Aerospace"), value: "aerospace" },
    { title: i18n.global.t("Biotechnology"), value: "biotechnology" },
    { title: i18n.global.t("Chemicals"), value: "chemicals" },
    { title: i18n.global.t("Consulting"), value: "consulting" },
    { title: i18n.global.t("Consumer Goods"), value: "consumer goods" },
    { title: i18n.global.t("Defense"), value: "defense" },
    { title: i18n.global.t("Electronics"), value: "electronics" },
    {
      title: i18n.global.t("Environmental Services"),
      value: "environmental services",
    },
    { title: i18n.global.t("Food And Beverage"), value: "food and beverage" },
    { title: i18n.global.t("Insurance"), value: "insurance" },
    { title: i18n.global.t("Mining"), value: "mining" },
    { title: i18n.global.t("Pharmaceuticals"), value: "pharmaceuticals" },
    { title: i18n.global.t("Public Sector"), value: "public sector" },
    { title: i18n.global.t("Shipping"), value: "shipping" },
    { title: i18n.global.t("Textiles"), value: "textiles" },
    { title: i18n.global.t("Warehousing"), value: "warehousing" },
  ]
}


export function getContactStatus() {
  return [
    { title: i18n.global.t("Active"), value: "active" },
    { title: i18n.global.t("Inactive"), value: "inactive" },
    { title: i18n.global.t("Resigned"), value: "resigned" },
    { title: i18n.global.t("Retired"), value: "retired" },
    { title: i18n.global.t("Suspended"), value: "suspended" },
    { title: i18n.global.t("Terminated"), value: "terminated" },
  ]
}

export function getBpStatus() {
  return [
    { title: i18n.global.t("Pending"), value: "pending" },
    { title: i18n.global.t("Active"), value: "active" },
    { title: i18n.global.t("Inactive"), value: "inactive" },
    { title: i18n.global.t("Blocked"), value: "blocked" },
  ]
}

export function getProductStatus() {
  return [
    { title: i18n.global.t("Active"), value: "active" },
    { title: i18n.global.t("Inactive"), value: "inactive" },
    { title: i18n.global.t("Draft"), value: "draft" },
  ]
}

export function getBpLegalStatus() {
  return [
    { title: i18n.global.t("Physical Person"), value: "physical person" },
    { title: i18n.global.t("SARL"), value: "sarl" },
    { title: i18n.global.t("SUARL"), value: "suarl" },
    { title: i18n.global.t("SA"), value: "sa" },
    { title: i18n.global.t("SNC"), value: "snc" },
  ]
}

export function getBpCertifications() {
  return [
    { title: i18n.global.t("ISO 9001:2015"), value: "iso 9001:2015" },
    { title: i18n.global.t("ISO 14001:2015"), value: "iso 14001:2015" },
    { title: i18n.global.t("ISO 45001:2018"), value: "iso 45001:2018" },
    { title: i18n.global.t("ISO 27001:2022"), value: "iso 27001:2022" },
    { title: i18n.global.t("ISO 22000:2018"), value: "iso 22000:2018" },
    { title: i18n.global.t("ISO 13485:2016"), value: "iso 13485:2016" },
    {
      title: i18n.global.t("ISO/IEC 20000-1:2018"),
      value: "iso/iec 20000-1:2018",
    },
    { title: i18n.global.t("ISO 50001:2018"), value: "iso 50001:2018" },
    { title: i18n.global.t("CE Marking"), value: "ce marking" },
    { title: i18n.global.t("GMP"), value: "gmp" },
    {
      title: i18n.global.t("HALAL Certification"),
      value: "halal certification",
    },
  ]
}

export function getProductCertifications() {
  return [
    { title: i18n.global.t("ISO 9001:2015 - Quality Management"), value: "iso 9001:2015" },
    { title: i18n.global.t("ISO 14001:2015 - Environmental Management"), value: "iso 14001:2015" },
    { title: i18n.global.t("ISO 45001:2018 - Occupational Health & Safety"), value: "iso 45001:2018" },
    { title: i18n.global.t("ISO 27001:2022 - Information Security"), value: "iso 27001:2022" },
    { title: i18n.global.t("ISO 22000:2018 - Food Safety Management"), value: "iso 22000:2018" },
    { title: i18n.global.t("ISO 13485:2016 - Medical Devices"), value: "iso 13485:2016" },
    { title: i18n.global.t("ISO/IEC 20000-1:2018 - IT Service Management"), value: "iso/iec 20000-1:2018" },
    { title: i18n.global.t("ISO 50001:2018 - Energy Management"), value: "iso 50001:2018" },
    { title: i18n.global.t("CE Marking - European Conformity"), value: "ce marking" },
    { title: i18n.global.t("GMP - Good Manufacturing Practices"), value: "gmp" },
    { title: i18n.global.t("HALAL Certification - Permissible Products"), value: "halal certification" },
    { title: i18n.global.t("Fair Trade Certification"), value: "fair trade certification" },
    { title: i18n.global.t("LEED Certification - Green Building"), value: "leed certification" },
    { title: i18n.global.t("BRCGS - Global Food Safety"), value: "brcgs" },
    { title: i18n.global.t("FSC Certification - Sustainable Forestry"), value: "fsc certification" },
    { title: i18n.global.t("Carbon Neutral Certification"), value: "carbon neutral certification" },
    { title: i18n.global.t("Energy Star Certification"), value: "energy star certification" },
    { title: i18n.global.t("Organic Certification"), value: "organic certification" },
  ]
}


export function getTeam() {
  return [
    { title: i18n.global.t("Karim Ben Salem"), value: "karim ben salem" },
    { title: i18n.global.t("Najla Ben Salem"), value: "najla ben salem" },
    { title: i18n.global.t("Mohamed Chraiti"), value: "mohamed chraiti" },
  ]
}

export function getPaymentTerms() {
  return [
    { title: i18n.global.t("Cash"), value: "cash" },
    { title: i18n.global.t("Net 30"), value: "net_30" },
    { title: i18n.global.t("Net 60"), value: "net_60" },
    { title: i18n.global.t("Net 90"), value: "net_90" },
    { title: i18n.global.t("Net 120"), value: "net_120" },
    { title: i18n.global.t("Due on Receipt"), value: "due_on_receipt" },
    { title: i18n.global.t("End of Month"), value: "end_of_month" },
  ]
}

export function getCompaniesSize() {
  return [
    { title: i18n.global.t("1-10 employees"), value: "1-10" },
    { title: i18n.global.t("11-50 employees"), value: "11-50" },
    { title: i18n.global.t("51-200 employees"), value: "51-200" },
    { title: i18n.global.t("201-500 employees"), value: "201-500" },
    { title: i18n.global.t("501-1000 employees"), value: "501-1000" },
    { title: i18n.global.t("1001-5000 employees"), value: "1001-5000" },
    { title: i18n.global.t("5001-10,000 employees"), value: "5001-10000" },
    { title: i18n.global.t("10,001+ employees"), value: "10001+" },
  ]
}

export function getDepartmentRoles() {
  return [
    {
      department: i18n.global.t("Sales & Business Development"),
      jobTitles: [
        { title: i18n.global.t("Account Manager"), value: "account manager" },
        { title: i18n.global.t("Business Development Manager"), value: "business Development manager" },
        { title: i18n.global.t("Sales Director"), value: "sales director" },
        { title: i18n.global.t("Key Account Executive"), value: "key account executive" },
        { title: i18n.global.t("Banking Relationship Manager"), value: "banking relationship manager" },        
        { title: i18n.global.t("Insurance Underwriter"), value: "insurance underwriter" },
      ],
    },
    {
      department: i18n.global.t("Marketing & Communications"),
      jobTitles: [
        { title: i18n.global.t("Marketing Coordinator"), value: "marketing coordinator" },
        { title: i18n.global.t("Product Manager"), value: "product manager" },
        { title: i18n.global.t("E-Commerce Specialist"), value: "e-commerce specialist" },
        { title: i18n.global.t("Digital Marketing Manager"), value: "digital marketing manager" },
      ],
    },
    {
      department: i18n.global.t("Finance & Accounting"),
      jobTitles: [
        { title: i18n.global.t("Finance Controller"), value: "finance controller" },
        { title: i18n.global.t("Chief Financial Officer (CFO)"), value: "cfo" },
        { title: i18n.global.t("Risk Analyst"), value: "risk analyst" },
        { title: i18n.global.t("Internal Auditor"), value: "internal auditor" },
      ],
    },
    {
      department: i18n.global.t("Human Resources (HR)"),
      jobTitles: [
        { title: i18n.global.t("HR Business Partner"), value: "hr business partner" },
        { title: i18n.global.t("Training & Development Manager"), value: "training-development manager" },
        { title: i18n.global.t("Recruitment Specialist"), value: "recruitment specialist" },
        { title: i18n.global.t("Diversity Officer"), value: "diversity officer" },
      ],
    },
    {
      department: i18n.global.t("Information Technology (IT)"),
      jobTitles: [
        { title: i18n.global.t("IT Manager"), value: "it manager" },
        { title: i18n.global.t("Chief Technology Officer (CTO)"), value: "cto" },
        { title: i18n.global.t("Software Architect"), value: "software architect" },
        { title: i18n.global.t("Technical Support Engineer"), value: "technical support engineer" },
        { title: i18n.global.t("Telecom Engineer"), value: "telecom engineer" },
        { title: i18n.global.t("Data Analyst"), value: "data analyst" },
        { title: i18n.global.t("Cloud Solutions Architect"), value: "cloud solutions architect" },

      ],
    },
    {
      department: i18n.global.t("Operations & Supply Chain"),
      jobTitles: [
        { title: i18n.global.t("Operations Manager"), value: "operations manager" },
        { title: i18n.global.t("Chief Operating Officer (COO)"), value: "Coo" },
        { title: i18n.global.t("Supply Chain Coordinator"), value: "supply chain coordinator" },
        { title: i18n.global.t("Logistics Coordinator"), value: "logistics coordinator" },
        { title: i18n.global.t("Manufacturing Supervisor"), value: "manufacturing supervisor" },
        { title: i18n.global.t("Retail Store Manager"), value: "retail store manager" },
      ],
    },
    {
      department: i18n.global.t("Legal & Compliance"),
      jobTitles: [
        { title: i18n.global.t("Legal Counsel"), value: "legal counsel" },
        { title: i18n.global.t("Compliance Officer"), value: "compliance officer" },
        { title: i18n.global.t("Regulatory Affairs Manager"), value: "regulatory affairs manager" },
        { title: i18n.global.t("Corporate Governance Specialist"), value: "corporate governance specialist" },
      ],
    },
    {
      department: i18n.global.t("Executive Leadership"),
      jobTitles: [
        { title: i18n.global.t("Chief Executive Officer (CEO)"), value: "ceo" },
        { title: i18n.global.t("Board Member"), value: "board member" },
        { title: i18n.global.t("Managing Director"), value: "managing director" },
        { title: i18n.global.t("Regional Director"), value: "regional director" },
      ],
    },
    {
      department: i18n.global.t("Healthcare & Pharmaceuticals"),
      jobTitles: [
        { title: i18n.global.t("Healthcare Administrator"), value: "healthcare administrator" },
        { title: i18n.global.t("Pharmaceutical Liaison"), value: "pharmaceutical liaison" },
        { title: i18n.global.t("Clinical Research Associate"), value: "clinical research associate" },
        { title: i18n.global.t("Medical Affairs Director"), value: "medical affairs director" },
      ],
    },
    {
      department: i18n.global.t("Engineering & Construction"),
      jobTitles: [
        { title: i18n.global.t("Construction Site Manager"), value: "construction site manager" },
        { title: i18n.global.t("Industrial Engineer"), value: "industrial engineer" },
        { title: i18n.global.t("Oil & Gas Field Operator"), value: "oil and gas field operator" },
        { title: i18n.global.t("Quality Assurance Specialist"), value: "quality assurance specialist" },
      ],
    },
    {
      department: i18n.global.t("Sustainability & ESG"),
      jobTitles: [
        { title: i18n.global.t("Sustainability Manager"), value: "sustainability manager" },
        { title: i18n.global.t("ESG Reporting Specialist"), value: "esg reporting specialist" },
        { title: i18n.global.t("Environmental Compliance Officer"), value: "environmental compliance officer" },
      ],
    },
    {
      department: i18n.global.t("International Expansion"),
      jobTitles: [
        { title: i18n.global.t("Global Business Manager"), value: "global business manager" },
        { title: i18n.global.t("Localization Specialist"), value: "localization specialist" },
        { title: i18n.global.t("Export Compliance Coordinator"), value: "export compliance coordinator" },
      ],
    },
    {
      department: i18n.global.t("Project Management"),
      jobTitles: [
        { title: i18n.global.t("Project Manager"), value: "project manager" },
        { title: i18n.global.t("Program Director"), value: "program director" },
        { title: i18n.global.t("Agile Scrum Master"), value: "agile scrum master" },
      ],
    },
    {
      department: i18n.global.t("Customer Service"),
      jobTitles: [
        { title: i18n.global.t("Customer Success Manager"), value: "customer success manager" },
        { title: i18n.global.t("Client Relations Specialist"), value: "client relations specialist" },
        { title: i18n.global.t("Service Delivery Manager"), value: "service delivery manager" },
      ],
    },
  ]
}

export function getDepartments() {
  return [
    { title: i18n.global.t("Sales & Business Development"), value: "sales" },
    { title: i18n.global.t("Information Technology (IT)"), value: "it" },
    { title: i18n.global.t("Operations & Supply Chain"), value: "operations" },
    { title: i18n.global.t("Marketing & Communications"), value: "marketing" },
    { title: i18n.global.t("Finance & Accounting"), value: "finance" },
    { title: i18n.global.t("Human Resources (HR)"), value: "hr" },
    { title: i18n.global.t("Legal & Compliance"), value: "legal" },
    { title: i18n.global.t("Executive Leadership"), value: "executive" },
    { title: i18n.global.t("Healthcare & Pharmaceuticals"), value: "healthcare" },
    { title: i18n.global.t("Engineering & Construction"), value: "engineering" },
    { title: i18n.global.t("Sustainability & ESG"), value: "sustainability" },
    { title: i18n.global.t("International Expansion"), value: "international" },
    { title: i18n.global.t("Project Management"), value: "project management" },
    { title: i18n.global.t("Customer Service"), value: "customer service" },
  ]
}

export function getAddressTypes() {
  return [
    { title: i18n.global.t("Corporate Headquarters"), value: "corporate headquarters" },
    { title: i18n.global.t("Primary"), value: "primary" },
    { title: i18n.global.t("Secondary"), value: "secondary" },
    { title: i18n.global.t("Billing Address"), value: "billing address" },
    { title: i18n.global.t("Shipping Address"), value: "shipping address" },
    { title: i18n.global.t("Office"), value: "office" },
    { title: i18n.global.t("Home/Residential"), value: "home/residential" },
    { title: i18n.global.t("Fleet Depot"), value: "fleet depot" },
    { title: i18n.global.t("Vehicle & Equipment Depot"), value: "vehicle & equipment depot" },
    { title: i18n.global.t("Asset Storage Hub"), value: "asset storage hub" },
    { title: i18n.global.t("Warehouse"), value: "warehouse" },
    { title: i18n.global.t("Store/Outlet"), value: "store/outlet" },
    { title: i18n.global.t("Branch Office"), value: "branch office" },
    { title: i18n.global.t("Factory"), value: "factory" },
    { title: i18n.global.t("Workshop"), value: "workshop" },
    { title: i18n.global.t("Mill"), value: "mill" },
    { title: i18n.global.t("Assembly Plant"), value: "assembly plant" },
    { title: i18n.global.t("Processing Plant"), value: "processing plant" },
  ]
}

export function getFireStations() {
  return [
    { title: i18n.global.t("Protection Civile Ech-Charguia: Ech-Charguia, Cité El Khadhra, Tunis Governorate. Phone: Not available."), value: "fire_station_tunis_01" },
    { title: i18n.global.t("Protection Civile Ben Arous: P6MG+Q49, A1, Ben Arous Governorate. Phone: +216 71 38 32 33."), value: "fire_station_ben_arous_01" },

    { title: i18n.global.t("Protection Civile SFAX : Avenue du Commandant Mohamed Béjaoui. Phone: +216 74 22 83 84."), value: "fire_station_sfax_01" },
    { title: i18n.global.t("Protection Civile SFAX Sakiet Ezzit: Rte de Tunis Km 10. Phone: +216 74 86 07 22."), value: "fire_station_sfax_02" },
    { title: i18n.global.t("Protection Civile SFAX Kerkennah: Ouled Bou Ali. Phone: +216 74 48 09 99."), value: "fire_station_sfax_03" },

    { title: i18n.global.t("Protection Civile Médenine: 8FQR+MV3, P19, Médenine Governorate. Phone: +216 75 64 04 81."), value: "fire_station_medenine_01" },
    { title: i18n.global.t("Protection Civile Djerba Midoun: Midoun, Djerba Midoun 4116, Médenine Governorate. Phone: +216 75 73 34 43."), value: "fire_station_medenine_02" },
    { title: i18n.global.t("Protection Civile Nabeul: Rue de Biélorussie, Nabeul 8000. Phone: +216 72 22 37 66."), value: "fire_station_nabeul_01" },
    { title: i18n.global.t("Protection Civile El Haouaria: Intersection MC 26 and MC 27, Al Huwariyah 8045. Phone: +216 72 26 95 75."), value: "fire_station_nabeul_02" },
    { title: i18n.global.t("Protection Civile Tataouine: XF48+GJ6, C111, Tataouine. Phone: +216 75 84 42 68."), value: "fire_station_tataouine_01" },
    { title: i18n.global.t("Protection Civile Jendouba: GQ46+GV6, P17, Jendouba 8100. Phone: +216 78 60 05 67."), value: "fire_station_jendouba_01" },
    { title: i18n.global.t("Protection Civile Ghardimaou: CCVR+36P, Ghardimaou. Phone: +216 78 66 22 00."), value: "fire_station_jendouba_02" },
    { title: i18n.global.t("Protection Civile Cebalat: V5RJ+5XM, Av. Fethi Zouhir - En Nekhilet, Cebalat. Phone: +216 23 84 34 56."), value: "fire_station_ariana_01" },
    { title: i18n.global.t("Protection Civile Monastir: QR6J+P3F, Monastir. Phone: +216 73 46 14 55."), value: "fire_station_monastir_01" },
    { title: i18n.global.t("Protection Civile Sousse: RJ6V+C7P, Sousse. Phone: +216 73 38 36 32."), value: "fire_station_sousse_01" },
    { title: i18n.global.t("Protection Civile La Manouba: Q3XJ+252, Manouba 2010. Phone: +216 71 60 06 04."), value: "fire_station_manouba_01" },
    { title: i18n.global.t("Protection Civile Bizerte: 7V95+5RQ, Rue Bourguiba, Bizerte. Phone: +216 72 43 10 22."), value: "fire_station_bizerte_01" },
    { title: i18n.global.t("Protection Civile Kairouan: M4C4+73H, Av. de Fès - Sidi Saad, Kairouan. Phone: +216 77 22 87 62."), value: "fire_station_kairouan_01" },
    { title: i18n.global.t("Protection Civile Kasserine: 5RHW+25X, P13, Kasserine. Phone: +216 77 47 44 90."), value: "fire_station_kasserine_01" },
    { title: i18n.global.t("Protection Civile Sbeïtla: 64MF+RFW, Sbeïtla. Phone: +216 71 00 01 98."), value: "fire_station_kasserine_02" },
    { title: i18n.global.t("Protection Civile Mahdia: G28G+XFF, C82, Hiboun. Phone: +216 73 67 05 51."), value: "fire_station_mahdia_01" },
    { title: i18n.global.t("Protection Civile Sidi Bouzid: 2FVX+84X, Bd Mohamed Bouazizi, Sidi Bouzid. Phone: +216 76 63 27 66."), value: "fire_station_sidi_bouzid_01" },
    { title: i18n.global.t("Protection Civile Regueb: VQ5Q+GGM, Regueb. Phone: Not available."), value: "fire_station_sidi_bouzid_02" },
    { title: i18n.global.t("Protection Civile Gaâfour: Gaâfour, Siliana. Phone: Not available."), value: "fire_station_siliana_01" },
    { title: i18n.global.t("Protection Civile Kébili: PX2M+MM2, Route Tourist, Kebili  Phone: +216 75 49 08 80."), value: "fire_station_kebili_01" },
    { title: i18n.global.t("Protection Civile Kef: 5M3R+MMG, Le Kef. Phone: Not available."), value: "fire_station_kef_01" },
    { title: i18n.global.t("Protection Civile Tozeur: PX2M+MM2, Route Tourist, Kebili. Phone: +216 75 49 08 80"), value: "fire_station_tozeur_01" },
    { title: i18n.global.t("Protection Civile Zaghouan: C46X+WJJ, Zaghouan. Phone: +216 72 67 52 73."), value: "fire_station_zaghouan_01" },
  ]
}


export function getCountries() {
  return [
    { title: i18n.global.t("Afghanistan"), value: "afghanistan" },
    { title: i18n.global.t("Albania"), value: "albania" },
    { title: i18n.global.t("Algeria"), value: "algeria" },
    { title: i18n.global.t("Andorra"), value: "andorra" },
    { title: i18n.global.t("Angola"), value: "angola" },
    {
      title: i18n.global.t("Antigua And Barbuda"),
      value: "antigua and barbuda",
    },
    { title: i18n.global.t("Argentina"), value: "argentina" },
    { title: i18n.global.t("Armenia"), value: "armenia" },
    { title: i18n.global.t("Australia"), value: "australia" },
    { title: i18n.global.t("Austria"), value: "austria" },
    { title: i18n.global.t("Azerbaijan"), value: "azerbaijan" },
    { title: i18n.global.t("Bahamas"), value: "bahamas" },
    { title: i18n.global.t("Bahrain"), value: "bahrain" },
    { title: i18n.global.t("Bangladesh"), value: "bangladesh" },
    { title: i18n.global.t("Barbados"), value: "barbados" },
    { title: i18n.global.t("Belarus"), value: "belarus" },
    { title: i18n.global.t("Belgium"), value: "belgium" },
    { title: i18n.global.t("Belize"), value: "belize" },
    { title: i18n.global.t("Benin"), value: "benin" },
    { title: i18n.global.t("Bhutan"), value: "bhutan" },
    { title: i18n.global.t("Bolivia"), value: "bolivia" },
    {
      title: i18n.global.t("Bosnia And Herzegovina"),
      value: "bosnia and herzegovina",
    },
    { title: i18n.global.t("Botswana"), value: "botswana" },
    { title: i18n.global.t("Brazil"), value: "brazil" },
    { title: i18n.global.t("Brunei"), value: "brunei" },
    { title: i18n.global.t("Bulgaria"), value: "bulgaria" },
    { title: i18n.global.t("Burkina Faso"), value: "burkina faso" },
    { title: i18n.global.t("Burundi"), value: "burundi" },
    { title: i18n.global.t("Cabo Verde"), value: "cabo verde" },
    { title: i18n.global.t("Cambodia"), value: "cambodia" },
    { title: i18n.global.t("Cameroon"), value: "cameroon" },
    { title: i18n.global.t("Canada"), value: "canada" },
    {
      title: i18n.global.t("Central African Republic"),
      value: "central african republic",
    },
    { title: i18n.global.t("Chad"), value: "chad" },
    { title: i18n.global.t("Chile"), value: "chile" },
    { title: i18n.global.t("China"), value: "china" },
    { title: i18n.global.t("Colombia"), value: "colombia" },
    { title: i18n.global.t("Comoros"), value: "comoros" },
    {
      title: i18n.global.t("Congo, Democratic Republic of the"),
      value: "congo, democratic republic of the",
    },
    {
      title: i18n.global.t("Congo, Republic of the"),
      value: "congo, republic of the",
    },
    { title: i18n.global.t("Costa Rica"), value: "costa rica" },
    { title: i18n.global.t("Croatia"), value: "croatia" },
    { title: i18n.global.t("Cuba"), value: "cuba" },
    { title: i18n.global.t("Cyprus"), value: "cyprus" },
    { title: i18n.global.t("Czech Republic"), value: "czech republic" },
    { title: i18n.global.t("Denmark"), value: "denmark" },
    { title: i18n.global.t("Djibouti"), value: "djibouti" },
    { title: i18n.global.t("Dominica"), value: "dominica" },
    { title: i18n.global.t("Dominican Republic"), value: "dominican republic" },
    { title: i18n.global.t("Ecuador"), value: "ecuador" },
    { title: i18n.global.t("Egypt"), value: "egypt" },
    { title: i18n.global.t("El Salvador"), value: "el salvador" },
    { title: i18n.global.t("Equatorial Guinea"), value: "equatorial guinea" },
    { title: i18n.global.t("Eritrea"), value: "eritrea" },
    { title: i18n.global.t("Estonia"), value: "estonia" },
    { title: i18n.global.t("Eswatini"), value: "eswatini" },
    { title: i18n.global.t("Ethiopia"), value: "ethiopia" },
    { title: i18n.global.t("Fiji"), value: "fiji" },
    { title: i18n.global.t("Finland"), value: "finland" },
    { title: i18n.global.t("France"), value: "france" },
    { title: i18n.global.t("Gabon"), value: "gabon" },
    { title: i18n.global.t("Gambia"), value: "gambia" },
    { title: i18n.global.t("Georgia"), value: "georgia" },
    { title: i18n.global.t("Germany"), value: "germany" },
    { title: i18n.global.t("Ghana"), value: "ghana" },
    { title: i18n.global.t("Greece"), value: "greece" },
    { title: i18n.global.t("Grenada"), value: "grenada" },
    { title: i18n.global.t("Guatemala"), value: "guatemala" },
    { title: i18n.global.t("Guinea"), value: "guinea" },
    { title: i18n.global.t("Guinea-Bissau"), value: "guinea-bissau" },
    { title: i18n.global.t("Guyana"), value: "guyana" },
    { title: i18n.global.t("Haiti"), value: "haiti" },
    { title: i18n.global.t("Honduras"), value: "honduras" },
    { title: i18n.global.t("Hungary"), value: "hungary" },
    { title: i18n.global.t("Iceland"), value: "iceland" },
    { title: i18n.global.t("India"), value: "india" },
    { title: i18n.global.t("Indonesia"), value: "indonesia" },
    { title: i18n.global.t("Iran"), value: "iran" },
    { title: i18n.global.t("Iraq"), value: "iraq" },
    { title: i18n.global.t("Ireland"), value: "ireland" },
    { title: i18n.global.t("Italy"), value: "italy" },
    { title: i18n.global.t("Jamaica"), value: "jamaica" },
    { title: i18n.global.t("Japan"), value: "japan" },
    { title: i18n.global.t("Jordan"), value: "jordan" },
    { title: i18n.global.t("Kazakhstan"), value: "kazakhstan" },
    { title: i18n.global.t("Kenya"), value: "kenya" },
    { title: i18n.global.t("Kiribati"), value: "kiribati" },
    { title: i18n.global.t("Korea, North"), value: "korea, north" },
    { title: i18n.global.t("Korea, South"), value: "korea, south" },
    { title: i18n.global.t("Kosovo"), value: "kosovo" },
    { title: i18n.global.t("Kuwait"), value: "kuwait" },
    { title: i18n.global.t("Kyrgyzstan"), value: "kyrgyzstan" },
    { title: i18n.global.t("Laos"), value: "laos" },
    { title: i18n.global.t("Latvia"), value: "latvia" },
    { title: i18n.global.t("Lebanon"), value: "lebanon" },
    { title: i18n.global.t("Lesotho"), value: "lesotho" },
    { title: i18n.global.t("Liberia"), value: "liberia" },
    { title: i18n.global.t("Libya"), value: "libya" },
    { title: i18n.global.t("Liechtenstein"), value: "liechtenstein" },
    { title: i18n.global.t("Lithuania"), value: "lithuania" },
    { title: i18n.global.t("Luxembourg"), value: "luxembourg" },
    { title: i18n.global.t("Madagascar"), value: "madagascar" },
    { title: i18n.global.t("Malawi"), value: "malawi" },
    { title: i18n.global.t("Malaysia"), value: "malaysia" },
    { title: i18n.global.t("Maldives"), value: "maldives" },
    { title: i18n.global.t("Mali"), value: "mali" },
    { title: i18n.global.t("Malta"), value: "malta" },
    { title: i18n.global.t("Marshall Islands"), value: "marshall islands" },
    { title: i18n.global.t("Mauritania"), value: "mauritania" },
    { title: i18n.global.t("Mauritius"), value: "mauritius" },
    { title: i18n.global.t("Mexico"), value: "mexico" },
    { title: i18n.global.t("Micronesia"), value: "micronesia" },
    { title: i18n.global.t("Moldova"), value: "moldova" },
    { title: i18n.global.t("Monaco"), value: "monaco" },
    { title: i18n.global.t("Mongolia"), value: "mongolia" },
    { title: i18n.global.t("Montenegro"), value: "montenegro" },
    { title: i18n.global.t("Morocco"), value: "morocco" },
    { title: i18n.global.t("Mozambique"), value: "mozambique" },
    { title: i18n.global.t("Myanmar"), value: "myanmar" },
    { title: i18n.global.t("Namibia"), value: "namibia" },
    { title: i18n.global.t("Nauru"), value: "nauru" },
    { title: i18n.global.t("Nepal"), value: "nepal" },
    { title: i18n.global.t("Netherlands"), value: "netherlands" },
    { title: i18n.global.t("New Zealand"), value: "new zealand" },
    { title: i18n.global.t("Nicaragua"), value: "nicaragua" },
    { title: i18n.global.t("Niger"), value: "niger" },
    { title: i18n.global.t("Nigeria"), value: "nigeria" },
    { title: i18n.global.t("North Macedonia"), value: "north macedonia" },
    { title: i18n.global.t("Norway"), value: "norway" },
    { title: i18n.global.t("Oman"), value: "oman" },
    { title: i18n.global.t("Pakistan"), value: "pakistan" },
    { title: i18n.global.t("Palau"), value: "palau" },
    { title: i18n.global.t("Palestine"), value: "palestine" },
    { title: i18n.global.t("Panama"), value: "panama" },
    { title: i18n.global.t("Papua New Guinea"), value: "papua new guinea" },
    { title: i18n.global.t("Paraguay"), value: "paraguay" },
    { title: i18n.global.t("Peru"), value: "peru" },
    { title: i18n.global.t("Philippines"), value: "philippines" },
    { title: i18n.global.t("Poland"), value: "poland" },
    { title: i18n.global.t("Portugal"), value: "portugal" },
    { title: i18n.global.t("Qatar"), value: "qatar" },
    { title: i18n.global.t("Romania"), value: "romania" },
    { title: i18n.global.t("Russia"), value: "russia" },
    { title: i18n.global.t("Rwanda"), value: "rwanda" },
    {
      title: i18n.global.t("Saint Kitts And Nevis"),
      value: "saint kitts and nevis",
    },
    { title: i18n.global.t("Saint Lucia"), value: "saint lucia" },
    {
      title: i18n.global.t("Saint Vincent And the Grenadines"),
      value: "saint vincent and the grenadines",
    },
    { title: i18n.global.t("Samoa"), value: "samoa" },
    { title: i18n.global.t("San Marino"), value: "san marino" },
    {
      title: i18n.global.t("Sao Tome And Principe"),
      value: "sao tome and principe",
    },
    { title: i18n.global.t("Saudi Arabia"), value: "saudi arabia" },
    { title: i18n.global.t("Senegal"), value: "senegal" },
    { title: i18n.global.t("Serbia"), value: "serbia" },
    { title: i18n.global.t("Seychelles"), value: "seychelles" },
    { title: i18n.global.t("Sierra Leone"), value: "sierra leone" },
    { title: i18n.global.t("Singapore"), value: "singapore" },
    { title: i18n.global.t("Slovakia"), value: "slovakia" },
    { title: i18n.global.t("Slovenia"), value: "slovenia" },
    { title: i18n.global.t("Solomon Islands"), value: "solomon islands" },
    { title: i18n.global.t("Somalia"), value: "somalia" },
    { title: i18n.global.t("South Africa"), value: "south africa" },
    { title: i18n.global.t("South Sudan"), value: "south sudan" },
    { title: i18n.global.t("Spain"), value: "spain" },
    { title: i18n.global.t("Sri Lanka"), value: "sri lanka" },
    { title: i18n.global.t("Sudan"), value: "sudan" },
    { title: i18n.global.t("Suriname"), value: "suriname" },
    { title: i18n.global.t("Sweden"), value: "sweden" },
    { title: i18n.global.t("Switzerland"), value: "switzerland" },
    { title: i18n.global.t("Syria"), value: "syria" },
    { title: i18n.global.t("Taiwan"), value: "taiwan" },
    { title: i18n.global.t("Tajikistan"), value: "tajikistan" },
    { title: i18n.global.t("Tanzania"), value: "tanzania" },
    { title: i18n.global.t("Thailand"), value: "thailand" },
    { title: i18n.global.t("Timor-Leste"), value: "timor-leste" },
    { title: i18n.global.t("Togo"), value: "togo" },
    { title: i18n.global.t("Tonga"), value: "tonga" },
    {
      title: i18n.global.t("Trinidad And Tobago"),
      value: "trinidad and tobago",
    },
    { title: i18n.global.t("Tunisia"), value: "tunisia" },
    { title: i18n.global.t("Turkey"), value: "turkey" },
    { title: i18n.global.t("Turkmenistan"), value: "turkmenistan" },
    { title: i18n.global.t("Tuvalu"), value: "tuvalu" },
    { title: i18n.global.t("Uganda"), value: "uganda" },
    { title: i18n.global.t("Ukraine"), value: "ukraine" },
    {
      title: i18n.global.t("United Arab Emirates"),
      value: "united arab emirates",
    },
    { title: i18n.global.t("United Kingdom"), value: "united kingdom" },
    { title: i18n.global.t("United States"), value: "united states" },
    { title: i18n.global.t("Uruguay"), value: "uruguay" },
    { title: i18n.global.t("Uzbekistan"), value: "uzbekistan" },
    { title: i18n.global.t("Vanuatu"), value: "vanuatu" },
    { title: i18n.global.t("Vatican City"), value: "vatican city" },
    { title: i18n.global.t("Venezuela"), value: "venezuela" },
    { title: i18n.global.t("Vietnam"), value: "vietnam" },
    { title: i18n.global.t("Yemen"), value: "yemen" },
    { title: i18n.global.t("Zambia"), value: "zambia" },
    { title: i18n.global.t("Zimbabwe"), value: "zimbabwe" },
  ]
}

export const preferredCurrency = [
  { title: "AED", value: "aed" },
  { title: "AFN", value: "afn" },
  { title: "ALL", value: "all" },
  { title: "AMD", value: "amd" },
  { title: "ANG", value: "ang" },
  { title: "AOA", value: "aoa" },
  { title: "ARS", value: "ars" },
  { title: "AUD", value: "aud" },
  { title: "AWG", value: "awg" },
  { title: "AZN", value: "azn" },
  { title: "BAM", value: "bam" },
  { title: "BBD", value: "bbd" },
  { title: "BDT", value: "bdt" },
  { title: "BGN", value: "bgn" },
  { title: "BHD", value: "bhd" },
  { title: "BIF", value: "bif" },
  { title: "BMD", value: "bmd" },
  { title: "BND", value: "bnd" },
  { title: "BOB", value: "bob" },
  { title: "BRL", value: "brl" },
  { title: "BSD", value: "bsd" },
  { title: "BTN", value: "btn" },
  { title: "BWP", value: "bwp" },
  { title: "BYN", value: "byn" },
  { title: "BZD", value: "bzd" },
  { title: "CAD", value: "cad" },
  { title: "CDF", value: "cdf" },
  { title: "CHF", value: "chf" },
  { title: "CLP", value: "clp" },
  { title: "CNY", value: "cny" },
  { title: "COP", value: "cop" },
  { title: "CRC", value: "crc" },
  { title: "CUP", value: "cup" },
  { title: "CVE", value: "cve" },
  { title: "CZK", value: "czk" },
  { title: "DJF", value: "djf" },
  { title: "DKK", value: "dkk" },
  { title: "DOP", value: "dop" },
  { title: "DZD", value: "dzd" },
  { title: "EGP", value: "egp" },
  { title: "ERN", value: "ern" },
  { title: "ETB", value: "etb" },
  { title: "EUR", value: "eur" },
  { title: "FJD", value: "fjd" },
  { title: "FKP", value: "fkp" },
  { title: "FOK", value: "fok" },
  { title: "GBP", value: "gbp" },
  { title: "GEL", value: "gel" },
  { title: "GGP", value: "ggp" },
  { title: "GHS", value: "ghs" },
  { title: "GIP", value: "gip" },
  { title: "GMD", value: "gmd" },
  { title: "GNF", value: "gnf" },
  { title: "GTQ", value: "gtq" },
  { title: "GYD", value: "gyd" },
  { title: "HKD", value: "hkd" },
  { title: "HNL", value: "hnl" },
  { title: "HRK", value: "hrk" },
  { title: "HTG", value: "htg" },
  { title: "HUF", value: "huf" },
  { title: "IDR", value: "idr" },
  { title: "ILS", value: "ils" },
  { title: "IMP", value: "imp" },
  { title: "INR", value: "inr" },
  { title: "IQD", value: "iqd" },
  { title: "IRR", value: "irr" },
  { title: "ISK", value: "isk" },
  { title: "JEP", value: "jep" },
  { title: "JMD", value: "jmd" },
  { title: "JOD", value: "jod" },
  { title: "JPY", value: "jpy" },
  { title: "KES", value: "kes" },
  { title: "KGS", value: "kgs" },
  { title: "KHR", value: "khr" },
  { title: "KMF", value: "kmf" },
  { title: "KPW", value: "kpw" },
  { title: "KRW", value: "krw" },
  { title: "KWD", value: "kwd" },
  { title: "KYD", value: "kyd" },
  { title: "KZT", value: "kzt" },
  { title: "LAK", value: "lak" },
  { title: "LBP", value: "lbp" },
  { title: "LKR", value: "lkr" },
  { title: "LRD", value: "lrd" },
  { title: "LSL", value: "lsl" },
  { title: "LYD", value: "lyd" },
  { title: "MAD", value: "mad" },
  { title: "MDL", value: "mdl" },
  { title: "MGA", value: "mga" },
  { title: "MKD", value: "mkd" },
  { title: "MMK", value: "mmk" },
  { title: "MNT", value: "mnt" },
  { title: "MOP", value: "mop" },
  { title: "MRU", value: "mru" },
  { title: "MUR", value: "mur" },
  { title: "MVR", value: "mvr" },
  { title: "MWK", value: "mwk" },
  { title: "MXN", value: "mxn" },
  { title: "MYR", value: "myr" },
  { title: "MZN", value: "mzn" },
  { title: "NAD", value: "nad" },
  { title: "NGN", value: "ngn" },
  { title: "NIO", value: "nio" },
  { title: "NOK", value: "nok" },
  { title: "NPR", value: "npr" },
  { title: "NZD", value: "nzd" },
  { title: "OMR", value: "omr" },
  { title: "PAB", value: "pab" },
  { title: "PEN", value: "pen" },
  { title: "PGK", value: "pgk" },
  { title: "PHP", value: "php" },
  { title: "PKR", value: "pkr" },
  { title: "PLN", value: "pln" },
  { title: "PYG", value: "pyg" },
  { title: "QAR", value: "qar" },
  { title: "RON", value: "ron" },
  { title: "RSD", value: "rsd" },
  { title: "RUB", value: "rub" },
  { title: "RWF", value: "rwf" },
  { title: "SAR", value: "sar" },
  { title: "SBD", value: "sbd" },
  { title: "SCR", value: "scr" },
  { title: "SDG", value: "sdg" },
  { title: "SEK", value: "sek" },
  { title: "SGD", value: "sgd" },
  { title: "SHP", value: "shp" },
  { title: "SLL", value: "sll" },
  { title: "SOS", value: "sos" },
  { title: "SRD", value: "srd" },
  { title: "SSP", value: "ssp" },
  { title: "STN", value: "stn" },
  { title: "SVC", value: "svc" },
  { title: "SYP", value: "syp" },
  { title: "SZL", value: "szl" },
  { title: "THB", value: "thb" },
  { title: "TJS", value: "tjs" },
  { title: "TMT", value: "tmt" },
  { title: "TND", value: "tnd" },
  { title: "TOP", value: "top" },
  { title: "TRY", value: "try" },
  { title: "TTD", value: "ttd" },
  { title: "TVD", value: "tvd" },
  { title: "TWD", value: "twd" },
  { title: "TZS", value: "tzs" },
  { title: "UAH", value: "uah" },
  { title: "UGX", value: "ugx" },
  { title: "USD", value: "usd" },
  { title: "UYU", value: "uyu" },
  { title: "UZS", value: "uzs" },
  { title: "VES", value: "ves" },
  { title: "VND", value: "vnd" },
  { title: "VUV", value: "vuv" },
  { title: "WST", value: "wst" },
  { title: "XAF", value: "xaf" },
  { title: "XCD", value: "xcd" },
  { title: "XOF", value: "xof" },
  { title: "XPF", value: "xpf" },
  { title: "YER", value: "yer" },
  { title: "ZAR", value: "zar" },
  { title: "ZMW", value: "zmw" },
  { title: "ZWL", value: "zwl" },
]

