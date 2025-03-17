export const saveRating = (
    userId,
    jokeId,
    score,
    joke,
    timecreated,
) => fetchMany([{
    methodname: 'mod_jokeofday_jokeofday_services',
    args: {
        userId,
        jokeId,
        score,
        joke,
        timecreated,
    },
}])[0];