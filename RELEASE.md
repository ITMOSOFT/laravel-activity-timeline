# Release instructions

- Repository: `ITMOSOFT/laravel-activity-timeline`
- Composer: `itmosoft/laravel-activity-timeline`
- Description: `Polymorphic activity feed and chronological event timeline for Laravel models, with actor attribution and structured properties.`

## Validate
```bash
composer install
composer test
```
Do not tag or publish until PHPUnit and GitHub Actions pass.

## Create repository and push (run locally)
Create a public repository named `laravel-activity-timeline` under ITMOSOFT:
```bash
git init
git add .
git status
git commit -m "feat: add Laravel activity timeline package"
git branch -M main
git remote add origin https://github.com/ITMOSOFT/laravel-activity-timeline.git
git push -u origin main
```

## Tag after tests and CI pass
```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

## Packagist
Submit `https://github.com/ITMOSOFT/laravel-activity-timeline` to Packagist and enable the GitHub service hook if prompted.

GitHub Release title: `v1.0.0 - Initial Release`.
