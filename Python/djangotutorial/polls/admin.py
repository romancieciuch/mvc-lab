from django.contrib import admin
from .models import Choice, Question

# 1. Definiujemy, jak mają wyglądać odpowiedzi (Choice) wewnątrz pytania
class ChoiceInline(admin.TabularInline):
    model = Choice
    extra = 3  # Tyle pustych pól na nowe odpowiedzi wyświetli się od razu

# 2. Konfigurujemy widok pytania (Question)
class QuestionAdmin(admin.ModelAdmin):
    # Grupowanie pól w sekcje (Date information można zwijać)
    fieldsets = [
        (None, {"fields": ["question_text"]}),
        ("Date information", {"fields": ["pub_date"], "classes": ["collapse"]}),
    ]
    # Dodanie odpowiedzi "w linii" pod pytaniem
    inlines = [ChoiceInline]

    # Co ma być widać na głównej liście pytań
    list_display = ["question_text", "pub_date", "was_published_recently"]

    # Dodanie filtrów po dacie po prawej stronie
    list_filter = ["pub_date"]

    # Dodanie wyszukiwarki po treści pytania
    search_fields = ["question_text"]

# 3. Rejestrujemy model z nowymi ustawieniami
admin.site.register(Question, QuestionAdmin)