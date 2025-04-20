import { createMongoAbility } from '@casl/ability'

export const initialAbility = [
  { action: 'read', subject: 'Auth' },
]

export const getRoleAbilities = roles => {
  // Super Admin: full access
  if (roles?.includes('Super Admin')) {
    return [...initialAbility, { action: 'manage', subject: 'all' }]
  }

  const abilities = []

  if (roles?.includes('Admin')) {
    abilities.push({ action: 'manage', subject: 'AdminDashboard' })
    abilities.push({ action: 'manage', subject: 'BusinessPartner' })
    abilities.push({ action: 'manage', subject: 'ProductAssetSuite' })    
  }

  if (roles?.includes('Operations Manager')) {
    abilities.push({ action: 'manage', subject: 'OperationsManagerDashboard' })
    abilities.push({ action: 'manage', subject: 'BusinessPartner' })
    abilities.push({ action: 'manage', subject: 'ProductAssetSuite' })
  }

  if (roles?.includes('Finance Manager')) {
    abilities.push({ action: 'manage', subject: 'FinanceManagerDashboard' })
    abilities.push({ action: 'manage', subject: 'BusinessPartner' })
  }

  if (roles?.includes('Technician')) {
    abilities.push({ action: 'manage', subject: 'TechnicianDashboard' })
  }

  if (roles?.includes('Client') || roles?.includes('Group Client')) {
    abilities.push({ action: 'manage', subject: 'ClientDashboard' })
  }

  return abilities.length ? [...initialAbility, ...abilities] : initialAbility
}

// Load stored abilities from localStorage if available
const storedAbilities = localStorage.getItem('userAbilities')
const existingAbilities = storedAbilities ? JSON.parse(storedAbilities) : initialAbility

// Create the CASL ability instance using createMongoAbility
const ability = createMongoAbility(existingAbilities)

export default ability
