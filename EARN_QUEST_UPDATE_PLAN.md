## Earn Quest Update Plan

- **Website Identity**
  - Update every occurrence of the product name to `Earn Quest` across UI copy, metadata, emails, and documentation.
  - Verify logos, favicons, and social previews reflect the new name.

- **Admin Interface Review**
  - Audit current admin flows; correct labels, grammar, and navigation where needed.
  - Ensure terminology aligns with the new package and referral rules.

- **Investment Packages**
  - Configure three packages: `35$`, `50$`, and `100$`.
  - Display package-specific deposit and withdrawal limits:
    - `35$` deposit → `24$` withdrawal cap.
    - `50$` deposit → `36$` withdrawal cap.
    - `100$` deposit → `81$` withdrawal cap.
  - Surface package details in both user dashboard and admin panels.

- **Referral-Based Withdrawal Rules**
  - Enforce: user must onboard at least one referral matching their package tier before any withdrawal.
  - Prevent withdrawals of the original investment amount (e.g., a `35$` depositor cannot withdraw the same `35$`).
  - Implement tiered referral requirements:
    - `35$` investors: require 1 referral of `35$`.
    - `50$` investors: require 2 referrals of `35$` (until 50$ tier referral logic is finalized).
    - `100$` investors: require 1 referral of `100$` **or** 3 referrals of `35$` (clarify mixed-tier handling in meeting).

- **Bind System (Treasure NFT Style)**
  - Design and document the “bind” mechanism inspired by Treasure NFT workflows.
  - Outline data structures, user flows, and admin controls; confirm requirements in upcoming meeting.

- **Admin User Insights**
  - Show referral counts per package tier (35$, 50$, 100$) for each user.
  - Display balances: total deposited, current wallet balance, total withdrawn.
  - Track withdrawal eligibility status based on referral fulfillment.

- **Payment Method Restriction**
  - Limit payouts to BEP20 wallet addresses.
  - Add validation to reject other address formats.

- **User-Side Referral View**
  - Update user dashboard to clearly show required and completed referrals by tier.
  - Highlight outstanding conditions preventing withdrawals.

- **Channel Subscription Task**
  - Add “Subscribe to our channel” as a mandatory task in the user task list.
  - Capture proof/confirmation mechanism for admin verification.

- **Levels & Future Discussion**
  - Document open questions for the multi-level structure to address in the next meeting.
  - Capture dependencies on the bind system and referral logic.

---

### Next Steps Checklist

- [ ] Confirm scope alignment with stakeholders (especially bind system and levels).
- [ ] Create detailed technical specs for referral enforcement and admin reporting.
- [ ] Update database schema/API contracts as needed for new metrics.
- [ ] Implement UI/UX changes across web and admin portals.
- [ ] Write automated tests covering package rules and withdrawal restrictions.
- [ ] Schedule review meeting for outstanding level design decisions.

