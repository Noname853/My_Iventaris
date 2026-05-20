import { PrismaClient } from '@/app/generated/prisma/client'
import { PrismaLibSql } from '@prisma/adapter-libsql'
import { createClient } from '@libsql/client'

const globalForPrisma = globalThis as unknown as { prisma: PrismaClient }

function createPrismaClient() {
  const raw = process.env.DATABASE_URL
  const url = raw && raw !== 'undefined' ? raw : 'file:./dev.db'
  const libsql = createClient({ url })
  return new PrismaClient({ adapter: new PrismaLibSql(libsql) })
}

export const prisma = globalForPrisma.prisma || createPrismaClient()

if (process.env.NODE_ENV !== 'production') globalForPrisma.prisma = prisma
