import { auth } from '@/lib/auth'
import { redirect } from 'next/navigation'
import { Sidebar } from '@/components/layout/Sidebar'
import { Topbar } from '@/components/layout/Topbar'
import { ReactNode } from 'react'

export default async function ProtectedLayout({ children }: { children: ReactNode }) {
  const session = await auth()
  if (!session) redirect('/login')

  return (
    <div className="flex h-screen overflow-hidden bg-black">
      {/* Sidebar — hidden on mobile */}
      <div className="hidden md:flex md:flex-shrink-0">
        <Sidebar role={session.user.role} />
      </div>

      {/* Main content */}
      <div className="flex flex-1 flex-col overflow-hidden">
        <Topbar userName={session.user.name} userRole={session.user.role} />
        <main className="flex-1 overflow-y-auto p-6">{children}</main>
      </div>
    </div>
  )
}
