-- ============================================================
-- CULTURE CLOSET — Supabase Schema Extensions
-- Run this in Supabase SQL Editor
-- ============================================================

-- 1. Add admin/moderation columns to existing tables
ALTER TABLE public.profiles
  ADD COLUMN IF NOT EXISTS is_suspended boolean DEFAULT false,
  ADD COLUMN IF NOT EXISTS coins integer DEFAULT 0;

ALTER TABLE public.listings
  ADD COLUMN IF NOT EXISTS status text DEFAULT 'pending'
    CHECK (status IN ('pending','approved','rejected')),
  ADD COLUMN IF NOT EXISTS is_featured boolean DEFAULT false,
  ADD COLUMN IF NOT EXISTS reject_reason text;

-- Set existing listings to approved (they were live before admin panel existed)
UPDATE public.listings SET status = 'approved' WHERE status IS NULL OR status = 'pending';

-- 2. Admin panel auth (separate from Supabase auth, internal only)
CREATE TABLE IF NOT EXISTS public.admin_users (
  id          uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  name        text NOT NULL,
  email       text UNIQUE NOT NULL,
  password    text NOT NULL, -- bcrypt hash
  created_at  timestamptz DEFAULT now(),
  updated_at  timestamptz DEFAULT now()
);

-- 3. Categories (used by both app and admin)
CREATE TABLE IF NOT EXISTS public.categories (
  id          uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  parent_id   uuid REFERENCES public.categories(id) ON DELETE SET NULL,
  name        text NOT NULL,
  slug        text UNIQUE NOT NULL,
  description text,
  icon        text,
  is_active   boolean DEFAULT true,
  sort_order  integer DEFAULT 0,
  created_at  timestamptz DEFAULT now(),
  updated_at  timestamptz DEFAULT now()
);

-- 4. FAQs (used by both app and admin)
CREATE TABLE IF NOT EXISTS public.faqs (
  id          uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  question    text NOT NULL,
  answer      text NOT NULL,
  category    text DEFAULT 'General',
  sort_order  smallint DEFAULT 0,
  is_active   boolean DEFAULT true,
  created_at  timestamptz DEFAULT now(),
  updated_at  timestamptz DEFAULT now()
);

-- 5. Blog posts (used by both app and admin)
CREATE TABLE IF NOT EXISTS public.blog_posts (
  id              uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  title           text NOT NULL,
  slug            text UNIQUE NOT NULL,
  excerpt         text,
  body            text NOT NULL,
  category        text,
  featured_image  text,
  status          text DEFAULT 'draft' CHECK (status IN ('draft','published')),
  published_at    timestamptz,
  created_at      timestamptz DEFAULT now(),
  updated_at      timestamptz DEFAULT now()
);

-- 6. Promo codes (used by both app and admin)
CREATE TABLE IF NOT EXISTS public.promo_codes (
  id          uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  code        text UNIQUE NOT NULL,
  description text,
  type        text NOT NULL CHECK (type IN ('percent','fixed')),
  value       numeric NOT NULL DEFAULT 0,
  min_order   numeric DEFAULT 0,
  max_uses    integer,
  used_count  integer DEFAULT 0,
  expires_at  timestamptz,
  is_active   boolean DEFAULT true,
  created_at  timestamptz DEFAULT now(),
  updated_at  timestamptz DEFAULT now()
);

-- 7. Platform settings (admin only)
CREATE TABLE IF NOT EXISTS public.platform_settings (
  id          uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  key         text UNIQUE NOT NULL,
  value       text,
  created_at  timestamptz DEFAULT now(),
  updated_at  timestamptz DEFAULT now()
);

-- 8. Contact/support messages (admin only)
CREATE TABLE IF NOT EXISTS public.contact_messages (
  id           uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  name         text NOT NULL,
  email        text NOT NULL,
  subject      text,
  body         text NOT NULL,
  status       text DEFAULT 'open' CHECK (status IN ('open','resolved')),
  admin_notes  text,
  resolved_at  timestamptz,
  created_at   timestamptz DEFAULT now(),
  updated_at   timestamptz DEFAULT now()
);
ALTER TABLE public.contact_messages ENABLE ROW LEVEL SECURITY;

-- 9. Cleaning queue (admin only)
CREATE TABLE IF NOT EXISTS public.cleaning_items (
  id           uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  rental_id    uuid REFERENCES public.rentals(id) ON DELETE CASCADE,
  listing_id   uuid REFERENCES public.listings(id) ON DELETE CASCADE,
  status       text DEFAULT 'pending' CHECK (status IN ('pending','in_progress','completed')),
  assigned_to  text,
  notes        text,
  completed_at timestamptz,
  created_at   timestamptz DEFAULT now(),
  updated_at   timestamptz DEFAULT now()
);

-- ============================================================
-- RLS Policies (app-facing tables — mobile reads these)
-- ============================================================

-- Categories: public read
ALTER TABLE public.categories ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "categories_public_read" ON public.categories;
CREATE POLICY "categories_public_read" ON public.categories
  FOR SELECT USING (is_active = true);

-- FAQs: public read (active only)
ALTER TABLE public.faqs ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "faqs_public_read" ON public.faqs;
CREATE POLICY "faqs_public_read" ON public.faqs
  FOR SELECT USING (is_active = true);

-- Blog: public read (published only)
ALTER TABLE public.blog_posts ENABLE ROW LEVEL SECURITY;
DROP POLICY IF EXISTS "blog_public_read" ON public.blog_posts;
CREATE POLICY "blog_public_read" ON public.blog_posts
  FOR SELECT USING (status = 'published');

-- Promo codes: app reads via service_role only (no public access)
ALTER TABLE public.promo_codes ENABLE ROW LEVEL SECURITY;

-- Platform settings, admin_users, cleaning_items: no public access
ALTER TABLE public.platform_settings ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.admin_users ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.cleaning_items ENABLE ROW LEVEL SECURITY;

-- ============================================================
-- Seed: initial admin user (password = admin1234)
-- Replace the hash below if you want a different password
-- ============================================================
INSERT INTO public.admin_users (name, email, password)
VALUES (
  'Vai',
  'vai@culturecloset.site',
  '$2y$12$LX6H3YTqBWmNRJCfxVwSROvSH5MFmVCUaBBCCVTLoSIWlCKANxVbG'
)
ON CONFLICT (email) DO NOTHING;
