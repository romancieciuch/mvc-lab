using Microsoft.EntityFrameworkCore;
using MvcMovie.Data; // Upewnij się, że namespace pasuje do Twojego projektu

namespace MvcMovie.Models;

public static class SeedData
{
    public static void Initialize(IServiceProvider serviceProvider)
    {
        using (var context = new MvcMovieContext(
            serviceProvider.GetRequiredService<
                DbContextOptions<MvcMovieContext>>()))
        {
            // Sprawdzenie, czy w bazie są już jakieś filmy
            if (context.Movie.Any())
            {
                return;   // Baza ma już dane, więc przerywamy działanie
            }

            // Jeśli jest pusta, dodajemy paczkę startową
            context.Movie.AddRange(
                new Movie
                {
                    Title = "When Harry Met Sally",
                    ReleaseDate = DateTime.Parse("1989-2-12"),
                    Genre = "Romantic Comedy",
                    Price = 7.99M,
                    Rating = "R"
                },
                new Movie
                {
                    Title = "Ghostbusters ",
                    ReleaseDate = DateTime.Parse("1984-3-13"),
                    Genre = "Comedy",
                    Price = 8.99M,
                    Rating = "G"
                }
            );

            // Zapisujemy zmiany w bazie
            context.SaveChanges();
        }
    }
}