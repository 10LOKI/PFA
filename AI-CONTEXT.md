# 📘 Context: ActTogether v2.0 - Strategic Architecture

## 🚀 Tech Stack
- **Backend**: Laravel 12 (PHP 8.3+) | Spatie Roles/Permissions
- **Frontend**: React.js (Vite + Tailwind CSS)
- **Database**: MySQL (Strict transactions for points)
- **Architecture**: Service Pattern + Action Classes (SOLID)
- **Reporting**: DomPDF / Browsershot (Certificate & CSR Export)

## 🎯 Core Business Logic (Cahier des Charges)
### 1. Tokenomics & Validation (Infalsifiable)
- **Emission**: Points are "mined" by real action only. No points on registration.
- **Calculation**: 1 hour = X points. Multipliers for "Urgent" or "High Impact" missions.
- **QR-Check-in**: Mandatory physical scan for point attribution. `checked_in_at` is the security lock.
- **Consumption**: Points used in Marketplace are "burned" (deducted from balance).

### 2. Gamification & Social Proof
- **Grades**: Novice, Pillar, Ambassador (unclocks Premium rewards).
- **Leaderboard**: Rolling 30-day activity score to prevent "old user" bias.
- **Social**: Likes, comments, and community visibility for events.

### 3. Professional Value
- **Certificates**: Auto-generation of official "Citizenship Engagement Certificates" (PDF).
- **CSR Dashboard**: Partner-side analytics (Total hours, Social impact, CSR stats export).

## 🛠️ Key Models & Schema
- `User`: Roles (Student, Partner, Admin).
- `Partner`: KYC-verified companies with CSR profiles.
- `Event`: Volunteering missions with quotas and QR generation.
- `PointsTransaction`: Immutable ledger. No updates/deletes.
- `EventUser`: Pivot tracking check-ins, certified hours, and partner feedback.
- `Reward`: Marketplace items with point costs.

## ⚖️ Governance & Rules
- **KYC**: Admin must validate partners before they can post missions.
- **Dispute Management**: Reporting system for student no-shows or dishonest partners.
- **Data Analytics**: Monitoring emission vs. consumption to prevent point inflation.

## 📝 Coding Standards
- **Transactions**: All point operations MUST be wrapped in DB Transactions.
- **Services**: Business logic stays in Services, not Controllers.
- **Security**: Strict middleware for Role-Based Access Control (RBAC).
- **API**: Clean JSON responses via API Resources.