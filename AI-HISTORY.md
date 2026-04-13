# AI Development Log - ActTogether

## Project Genesis
- **Date:** April 2026
- **Objective:** Build a gamified volunteering platform (Laravel/React).
- **Core Engine:** Points Transaction Ledger (Immutable).

## Milestone 1: Infrastructure & Tokenomics
- [x] Database Schema Design (Users, Events, Points, Rewards).
- [x] Implementation of the `points_transactions` migration.
- [x] Implementation of the `event_user` QR-check-in pivot.

## Milestone 2: Full Schema & Eloquent Layer
- **Date:** April 2026

### Migrations (13 tables)
- [x] `users` — role, grade, points_balance, total_hours, kyc_verified, establishment_id
- [x] `establishments` — school/university registry for inter-school leaderboard
- [x] `parteners` — KYC profile, rc_number, rc_document, is_certified
- [x] `events` — quota, duration_hours, points_reward, urgency_multiplier, qr_code_token
- [x] `event_user` — checked_in_at (QR lock), points_earned, partner_rating
- [x] `points_transactions` — immutable ledger, balance_after snapshot, morphable source
- [x] `rewards` — points_cost, stock, min_grade gate, is_premium
- [x] `reward_redemptions` — points_spent, status enum
- [x] `grades` — user academic level per establishment
- [x] `comments` — polymorphic (commentable)
- [x] `feedbacks` — polymorphic (feedbackable), rating 1-5
- [x] `cache`, `jobs` — Laravel defaults (untouched)

### Models (11 models)
- [x] `Establishment` — hasMany users, grades
- [x] `User` — full fillable, casts, role helpers (isAdmin/isPartner/isStudent), all relationships
- [x] `Partner` — $table='parteners', isApproved(), events via partner_id/user_id
- [x] `Grade` — belongsTo user + establishment
- [x] `Event` — effectivePoints(), isFull(), morphMany comments/feedbacks
- [x] `EventUser` (Pivot) — hasCheckedIn() QR guard, $incrementing=true
- [x] `PointsTransaction` — UPDATED_AT=null, boot() blocks update/delete (LogicException)
- [x] `Reward` — isAvailable(), isAccessibleBy(User) grade gate hierarchy
- [x] `RewardRedemption` — status helpers, morphMany pointsTransactions as source
- [x] `Comment` — polymorphic commentable
- [x] `Feedback` — polymorphic feedbackable, isValidRating()

### Known Risk (to address in Service layer)
- `users.points_balance` is mutable — must only be written by `PointsService` inside `DB::transaction()`.
  Never call `$user->update(['points_balance' => X])` directly anywhere in the codebase.

## Current Focus
- Breeze installation (API mode for React frontend)
- Spatie Laravel-Permission setup for 3 roles: student, partner, admin
