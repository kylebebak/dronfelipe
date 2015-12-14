<article>


<h3>
  Stretching of visits: worst case scenario
</h3>


<p>
  Here we describe a visit that gets streched as records are added to it. We will define the visit's diameter <code>d</code> as the largest distance between any pair of records in the visit.
</p>

<p>
  We consider a sequence starting with an arbitrarily placed record that instantiates a new visit <code>V</code>. The following records radiate out in one direction, such that as each record is added to <code>V</code>, the following record is at distance <code>R</code> from the newly calculated center of <code>V</code>. As each record is added, it stretches <code>V</code> as much as possible. The stretching slows down as <code>V</code> accumulates records and its "weight" increases. We want to know if this stretching is bounded, and if it's not, what function we can use to describe it.
</p>

<p>
  We focus on the growth of <code>d</code> as a function of <code>n</code>, the number of records that have been added to <code>V</code>. We assume <code>R=1</code> to simplify our expressions.
</p>

<ul class="formula">

  <li>
    `d_1 = 0` <code>first record in visit</code>
  </li>
  <li>
    `d_2 = (1 * d_1 + (d_1 + 1))/2 = (2 * d_1 + 1)/2 = d_1 + 1/2 = 1/2`
  </li>
  <li>
    `d_3 = (2 * d_2 + (d_2 + 1))/3 = (3 * d_2 + 1)/3 = d_2 + 1/3 = 1/2 + 1/3`
  </li>
  <li>
    `d_4 = (3 * d_3 + (d_3 + 1))/4 = (4 * d_3 + 1)/4 = d_3 + 1/4 = 1/2 + 1/3 + 1/4`
  </li>

</ul>

<p>
  It's clear that `AAn >= 2, d_n = 1/2 + 1/3 + ... + 1/n = sum_(i=2)^n 1/n`. But this is just <a href="https://en.wikipedia.org/wiki/Harmonic_series_(mathematics)">the harmonic series</a>, minus the first term. The growth of this series is logarithmic, and indeed for our series we can show `AAn >= 2, d_n < ln(n)`.
</p>
</article>
